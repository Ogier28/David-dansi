# Déploiement — GitHub + Render

Ce document décrit la mise en ligne du portfolio, de la machine locale à une URL
publique partageable. Comptez une quinzaine de minutes la première fois.

---

## 1. Les fichiers ajoutés pour le déploiement

| Fichier | À quoi il sert |
|---|---|
| `Dockerfile` | Recette de l'image de production. Elle compile les assets avec Node, installe les dépendances PHP avec Composer, puis assemble une image finale contenant Nginx + PHP-FPM — sans Node ni Composer. |
| `.dockerignore` | Liste ce qui ne doit **jamais** entrer dans l'image : `vendor/`, `node_modules/`, `.env`, la base SQLite locale, l'historique Git. |
| `docker/entrypoint.sh` | Script lancé au démarrage du conteneur : il vérifie `APP_KEY`, écrit la configuration Nginx avec le port imposé par Render, met en cache config/routes/vues, applique les migrations, puis démarre le serveur. |
| `docker/nginx/nginx.conf.template` | Configuration Nginx. C'est un modèle parce que le port d'écoute n'est connu qu'à l'exécution (variable `PORT`). |
| `docker/php/php.ini` | Réglages PHP de production : erreurs masquées côté visiteur, OPcache activé. |
| `docker/php/php-fpm.conf` | Taille du pool PHP-FPM et redirection des logs vers la sortie du conteneur. |
| `docker/supervisord.conf` | Fait tourner Nginx et PHP-FPM ensemble dans le conteneur et arrête tout si l'un des deux meurt, pour que Render relance le service. |
| `render.yaml` | Décrit l'infrastructure : une base PostgreSQL managée et un service web Docker, avec toutes les variables d'environnement. Render appelle ça un **Blueprint**. |

Deux fichiers existants ont été ajustés :

- `bootstrap/app.php` : prise en charge de la variable `TRUSTED_PROXIES`. Render
  place le conteneur derrière un répartiteur de charge ; sans cette déclaration
  Laravel croirait répondre en HTTP (URL canoniques erronées) et le limiteur de
  débit verrait la même adresse IP pour tous les visiteurs.
- `.env.example` : ajout de `TRUSTED_PROXIES`, de `SESSION_SECURE_COOKIE` et des
  variables de base de données PostgreSQL.

---

## 2. Pousser le projet sur GitHub

Le dépôt local est déjà initialisé avec un premier commit. Créez un dépôt
**vide** sur [github.com/new](https://github.com/new) — sans README, sans
`.gitignore`, sans licence, sinon les historiques divergent — puis :

```bash
cd /home/ogier/David

# En HTTPS (vous donnerez votre identifiant et un token d'accès personnel)
git remote add origin https://github.com/VOTRE-UTILISATEUR/VOTRE-DEPOT.git

# …ou en SSH si vous avez déjà une clé enregistrée sur GitHub
# git remote add origin git@github.com:VOTRE-UTILISATEUR/VOTRE-DEPOT.git

git push -u origin main
```

Vérification : `git remote -v` doit afficher votre dépôt, et la page GitHub
doit montrer `Dockerfile`, `render.yaml` et `DEPLOY.md` à la racine.

> **Dépôt public ou privé ?** Les deux fonctionnent avec Render. Aucun secret
> n'est versionné (`.env` est ignoré, `render.yaml` ne contient que des
> placeholders), donc un dépôt public ne pose pas de problème de sécurité.

---

## 3. Créer les services sur Render

1. Générez d'abord la clé de chiffrement de l'application, en local :

   ```bash
   php artisan key:generate --show
   ```

   Copiez la valeur affichée, de la forme `base64:xxxxxxxx…`. Gardez-la de côté,
   vous allez la coller à l'étape 4. **Ne la committez jamais.**

2. Sur [dashboard.render.com](https://dashboard.render.com), cliquez
   **New +** → **Blueprint**.

3. Connectez votre compte GitHub si ce n'est pas déjà fait, autorisez l'accès au
   dépôt, puis sélectionnez-le.

4. Render lit `render.yaml` et affiche les deux ressources qu'il va créer :
   le service web `dansi-david-portfolio` et la base `portfolio-db`.

5. Render demande alors la valeur des variables non versionnées (voir le
   tableau de l'étape 4). Remplissez au minimum `APP_KEY`.

6. Cliquez **Apply**. Render crée la base, puis construit l'image Docker. Le
   premier build prend entre 5 et 12 minutes (compilation des assets et
   installation des dépendances).

Les variables `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME` et `DB_PASSWORD`
sont câblées automatiquement sur la base créée : vous n'avez rien à y saisir.

---

## 4. Variables à renseigner à la main

Dans **Dashboard → dansi-david-portfolio → Environment**.

| Variable | Obligatoire | Valeur / où la trouver |
|---|---|---|
| `APP_KEY` | **Oui** | Sortie de `php artisan key:generate --show`. Sans elle le conteneur refuse de démarrer, avec un message explicite dans les logs. |
| `APP_URL` | Oui, après le 1er déploiement | L'URL publique attribuée par Render, par exemple `https://dansi-david-portfolio.onrender.com`. Vous ne la connaissez qu'une fois le service créé : laissez vide au départ, renseignez-la ensuite. Elle sert aux URL absolues générées hors requête HTTP. |
| `CONTACT_RECIPIENT` | Recommandé | L'adresse e-mail qui reçoit les demandes du formulaire. À défaut, `MAIL_FROM_ADDRESS` est utilisée. |
| `TURNSTILE_SITE_KEY` | Non | Clé publique du captcha invisible. Sur [dash.cloudflare.com](https://dash.cloudflare.com/?to=/:account/turnstile) : **Add widget**, nommez-le, ajoutez votre domaine Render dans *Hostnames*, type **Managed**. Cloudflare affiche alors une *Site Key* et une *Secret Key*, gratuitement. |
| `TURNSTILE_SECRET_KEY` | Non | Clé secrète du même widget. **Les deux doivent être renseignées ensemble** : le formulaire n'active la vérification que si la clé secrète est présente. |
| `MAIL_MAILER` | Non | Vaut `log` par défaut : les messages du formulaire sont enregistrés en base et tracés dans les logs, mais **aucun e-mail ne part**. Passez-la à `smtp` pour recevoir les notifications. |
| `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_SCHEME`, `MAIL_FROM_ADDRESS` | Si `MAIL_MAILER=smtp` | Fournis par votre service d'envoi (Brevo, Mailgun, Postmark, Resend, ou le SMTP de votre hébergeur). Exemple Brevo : hôte `smtp-relay.brevo.com`, port `587`, `MAIL_SCHEME=tls`. |

Chaque modification de variable déclenche un redéploiement automatique du
service.

> Même sans Turnstile, le formulaire reste protégé par quatre barrières : le
> jeton CSRF, le champ honeypot, le délai minimum de 3 secondes entre
> l'affichage et l'envoi, et le limiteur de débit (3 envois par minute et 20 par
> jour et par adresse IP).

---

## 5. Vérifier que le site est bien en ligne

Dès que Render affiche **Live** :

1. **Santé du service** — ouvrez `https://VOTRE-URL.onrender.com/up`.
   La réponse doit être une page de statut en HTTP 200. C'est l'adresse que
   Render interroge lui-même pour décider si le conteneur est sain.

2. **Les pages** — vérifiez dans l'ordre :

   | URL | Attendu |
   |---|---|
   | `/` | Redirection vers `/fr` ou `/en` selon la langue du navigateur |
   | `/fr` | Page d'accueil complète, polices et couleurs correctes |
   | `/fr/realisations` | Grille des projets, filtres par catégorie fonctionnels |
   | `/fr/realisations/audit-energetique-campus` | Page de détail |
   | `/en` | Version anglaise, sélecteur de langue opérationnel |
   | `/sitemap.xml` | XML listant les deux langues, en **https://** |
   | `/robots.txt` | Contenu texte avec la ligne `Sitemap:` |

   Si le style est absent ou cassé, c'est que les assets n'ont pas été compilés :
   regardez l'étape `npm run build` dans les logs de build.

3. **HTTPS et en-têtes** — en ligne de commande :

   ```bash
   curl -sI https://VOTRE-URL.onrender.com/fr | grep -i "content-security-policy\|strict-transport\|x-frame"
   ```

   Les trois en-têtes doivent être présents. `Strict-Transport-Security`
   n'apparaît que si `TRUSTED_PROXIES` est bien positionné : son absence
   signifie que Laravel ne voit pas la requête comme sécurisée.

4. **URL canoniques** — vérifiez qu'elles pointent en HTTPS :

   ```bash
   curl -s https://VOTRE-URL.onrender.com/fr | grep -o '<link rel="canonical"[^>]*>'
   ```

5. **Le formulaire de contact** — envoyez un message de test depuis `/fr`.
   Vous devez voir le message de confirmation. Vérifiez ensuite l'enregistrement
   en base, depuis **Dashboard → portfolio-db → Connect → PSQL Command** :

   ```sql
   SELECT id, name, email, subject, created_at FROM contact_messages ORDER BY id DESC LIMIT 5;
   ```

6. **Les logs** — onglet **Logs** du service. Les lignes `[entrypoint]` au
   démarrage racontent les migrations et la mise en cache ; les erreurs PHP y
   apparaissent aussi (le disque du conteneur étant éphémère, rien n'est écrit
   dans `storage/logs`).

---

## 6. Redéployer après une modification

Le déploiement est **automatique** : `autoDeploy: true` dans `render.yaml` fait
que chaque push sur `main` déclenche un nouveau build.

```bash
git add -A
git commit -m "Mise à jour du contenu"
git push
```

Render reconstruit l'image, applique les migrations éventuelles et bascule sur
la nouvelle version sans coupure. Suivez l'avancement dans l'onglet **Events**.

Autres cas :

- **Redéployer sans changement de code** (par exemple après avoir modifié une
  variable) : **Manual Deploy** → *Deploy latest commit*.
- **Repartir d'une image propre** : **Manual Deploy** → *Clear build cache &
  deploy*. Utile si un asset semble resté en cache.
- **Revenir en arrière** : onglet **Events**, puis *Rollback* sur un
  déploiement précédent.
- **Modifier `render.yaml`** : un push suffit, mais Render ne réapplique pas
  automatiquement les changements d'infrastructure. Passez par
  **Blueprints → votre blueprint → Sync** pour les prendre en compte.

---

## 7. Points d'attention

### Le plan gratuit et ses limites

- **La base PostgreSQL gratuite expire 30 jours après sa création** et est
  ensuite supprimée par Render, avec ses données. Pour un site durable, passez
  `plan: free` à `plan: basic-256mb` dans `render.yaml` pour la base (environ 6 $
  par mois) avant l'échéance, ou exportez régulièrement vos messages de contact.
- **Un service web gratuit s'endort** après 15 minutes sans trafic. La première
  visite suivante attend 30 à 60 secondes le temps du réveil. Le plan `starter`
  (environ 7 $ par mois) supprime ce délai — à considérer si vous partagez le
  lien à des recruteurs ou des clients.

### Le disque du conteneur est éphémère

Tout ce qui est écrit dans le conteneur disparaît à chaque déploiement ou
redémarrage. C'est pourquoi les sessions, le cache et la file d'attente sont
configurés sur PostgreSQL, et les logs envoyés sur la sortie standard. **Si vous
ajoutez un jour un envoi de fichiers** (CV téléversé, images gérées depuis une
interface), il faudra un stockage externe de type S3 : un disque Render
persistant est possible mais incompatible avec les déploiements sans coupure.

Les images actuelles du site (portrait, visuels de projets, image de partage)
sont versionnées dans `public/images` : elles sont donc incluses dans l'image
Docker et ne posent aucun problème.

### Contrôles de sécurité effectués

- Aucune clé, mot de passe ni token en dur dans le code : toutes les valeurs
  sensibles passent par `env()` depuis `config/`, et `render.yaml` ne contient
  que des placeholders.
- `APP_DEBUG=false` et `APP_ENV=production` sont forcés dans `render.yaml` ;
  l'entrypoint affiche un avertissement dans les logs si quelqu'un les change.
- `.env` est ignoré par Git (`.gitignore`) **et** exclu de l'image Docker
  (`.dockerignore`). La base SQLite locale l'est aussi.
- Aucune route de debug : les seules routes publiques sont les pages du site,
  `sitemap.xml`, `robots.txt` et `/up`. Aucun `dd()` ni `dump()` oublié dans le
  code.
- `display_errors = Off` dans `docker/php/php.ini` : même en cas d'erreur PHP
  brute, aucune trace n'est renvoyée au visiteur.
- Le cookie de session est marqué `Secure` (`SESSION_SECURE_COOKIE=true`) et la
  base n'accepte aucune connexion extérieure à Render (`ipAllowList: []`).

### Non vérifiable depuis cet environnement

Docker n'est pas installé sur la machine de développement : l'image n'a donc
**pas pu être construite ni exécutée en local**. Le Dockerfile suit les
pratiques habituelles et chaque fichier de configuration a été relu, mais le
premier build sur Render reste le vrai test. Si une étape échoue, le log de
build indique précisément laquelle ; les causes les plus probables sont une
extension PHP manquante ou un échec de `npm ci`.

Pour tester en local, si vous installez Docker un jour :

```bash
docker build -t portfolio .

# Test rapide sans base de données : sessions en cookie, cache sur disque,
# migrations ignorées. Suffisant pour vérifier que les pages s'affichent.
docker run --rm -p 8080:10000 \
  -e APP_KEY="$(php artisan key:generate --show)" \
  -e APP_ENV=production \
  -e APP_DEBUG=false \
  -e SKIP_MIGRATIONS=true \
  -e SESSION_DRIVER=cookie \
  -e CACHE_STORE=file \
  portfolio
```

Puis ouvrez `http://localhost:8080`. Le formulaire de contact ne fonctionnera
pas dans ce mode (il a besoin de la base), le reste du site si.

---

## 8. Après la mise en ligne

Le contenu livré (projets, parcours, témoignages, portrait) est fictif. La liste
complète de ce qu'il faut remplacer se trouve à la fin du `README.md`, section
« À remplacer avant la mise en ligne ».

Deux actions SEO à ne pas oublier une fois l'URL définitive connue :

1. Renseigner `APP_URL` (étape 4) puis vérifier `/sitemap.xml`.
2. Déclarer le site dans la [Google Search Console](https://search.google.com/search-console)
   et y soumettre le sitemap.
