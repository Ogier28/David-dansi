# Portfolio — Dansi David, ingénieur énergétique

Site vitrine bilingue (français / anglais) construit sur Laravel 13, Tailwind CSS v4 et Alpine.js.
Thème sombre, cartes en verre dépoli, accent or. Aucun service tiers n'est appelé au chargement
des pages : polices auto-hébergées, aucune mesure d'audience, aucun cookie publicitaire.

---

## Sommaire

1. [Prérequis](#prérequis)
2. [Installation](#installation)
3. [Lancer le site en développement](#lancer-le-site-en-développement)
4. [Modifier le contenu](#modifier-le-contenu)
5. [Images](#images)
6. [Langues et URL](#langues-et-url)
7. [Design system](#design-system)
8. [Sécurité du formulaire de contact](#sécurité-du-formulaire-de-contact)
9. [En-têtes de sécurité](#en-têtes-de-sécurité)
10. [Référencement](#référencement)
11. [Tests](#tests)
12. [Mise en production](#mise-en-production)
13. [Déploiement sur Render](#déploiement-sur-render)
14. [À remplacer avant la mise en ligne](#à-remplacer-avant-la-mise-en-ligne)

---

## Prérequis

| Outil | Version |
| --- | --- |
| PHP | 8.3 ou plus, avec les extensions `pdo_sqlite`, `mbstring`, `intl`, `gd`, `zip`, `curl` |
| Composer | 2.x |
| Node.js | 20 ou plus |

La base de données est un fichier SQLite en développement. Le passage à MySQL ou PostgreSQL ne
demande que de changer les variables `DB_*` du fichier `.env` : aucune requête n'est spécifique à SQLite.

## Installation

```bash
composer install
npm install

cp .env.example .env
php artisan key:generate

touch database/database.sqlite
php artisan migrate
```

## Lancer le site en développement

Deux processus, dans deux terminaux :

```bash
php artisan serve      # http://localhost:8000
npm run dev            # compilation des styles et scripts, rechargement à chaud
```

Ou en une seule commande, si vous préférez :

```bash
composer run dev
```

Pour produire les fichiers de production :

```bash
npm run build
```

> **Si une page s'affiche sans aucun style**, c'est que les assets ne sont pas compilés :
> lancez `npm run dev` ou `npm run build`. Tailwind v4 lit les classes directement dans les
> fichiers Blade, donc **toute modification d'un template demande une recompilation**.

---

## Modifier le contenu

Le contenu est séparé en deux : la **structure** vit dans la configuration, les **textes** dans les
fichiers de langue. Aucun contenu n'est en base de données, hors messages reçus.

| Ce que vous voulez changer | Fichier |
| --- | --- |
| Nom, courriel, téléphone, ville, LinkedIn, portrait | `config/portfolio.php` → `identity` |
| Chiffres clés de l'accueil | `config/portfolio.php` → `metrics` |
| Domaines d'expertise (ordre, icône, largeur de carte) | `config/portfolio.php` → `expertise` |
| Projets (slug, catégorie, année, image, chiffres, outils) | `config/portfolio.php` → `projects` |
| Logiciels et niveaux, certifications | `config/portfolio.php` → `skills` |
| Parcours (dates, type) | `config/portfolio.php` → `timeline` |
| Afficher ou masquer les témoignages | `config/portfolio.php` → `sections.testimonials` |
| **Tous les textes français** | `lang/fr/*.php` |
| **Tous les textes anglais** | `lang/en/*.php` |

Chaque entrée de la configuration porte une `key` qui sert à retrouver ses textes. Par exemple le
projet `campus_audit` tire son titre de `lang/fr/projects.php` → `items.campus_audit.title`.

### Ajouter un projet

1. Ajouter une entrée dans `config/portfolio.php` → `projects`, avec une `key` unique et un `slug`
   par langue.
2. Ajouter le bloc de textes correspondant dans `lang/fr/projects.php` **et** `lang/en/projects.php`,
   sous la même `key`.
3. Déposer l'image dans `public/images/projects/` (voir ci-dessous).

Le projet apparaît alors sur la page des réalisations, dans le plan de site et dans les filtres.
`'featured' => true` le place aussi dans la sélection de la page d'accueil.

### Masquer une section

Passer la valeur correspondante à `false` dans `config/portfolio.php` → `sections`. La section
disparaît de la page et du rail de navigation, sans laisser d'espace vide.

---

## Images

| Visuel | Emplacement | Dimensions |
| --- | --- | --- |
| Projets | `public/images/projects/*.webp` | 1600 × 1000 (ratio 8/5) |
| Portrait | `public/images/portrait.webp` | 1000 × 1250 (ratio 4/5) |
| Carte de partage | `public/images/og-default.png` | 1200 × 630 |
| Icône de navigateur | `public/favicon.svg` et `public/apple-touch-icon.png` | 180 × 180 pour le PNG |

Les dimensions sont écrites en dur dans les gabarits (`width` / `height`) pour éviter tout décalage
de mise en page au chargement. **Conservez les mêmes ratios** en remplaçant les images.

Conversion d'une photo en WebP :

```bash
cwebp -q 82 -resize 1600 0 photo.jpg -o public/images/projects/mon-projet.webp
```

---

## Langues et URL

Les deux langues sont déclarées dans `config/portfolio.php` → `locales`, et leurs segments d'URL
dans `route_slugs` :

| Page | Français | Anglais |
| --- | --- | --- |
| Accueil | `/fr` | `/en` |
| Réalisations | `/fr/realisations` | `/en/projects` |
| Détail d'un projet | `/fr/realisations/{slug}` | `/en/projects/{slug}` |
| Mentions légales | `/fr/mentions-legales` | `/en/legal-notice` |
| Confidentialité | `/fr/politique-de-confidentialite` | `/en/privacy-policy` |

`/` ne sert aucun contenu : la langue est négociée avec le navigateur (`Accept-Language`), puis
mémorisée dans un cookie pour les visites suivantes.

Dans les gabarits, les liens passent par le helper `localized_route()` :

```blade
<a href="{{ localized_route('projects.index') }}">…</a>
<a href="{{ localized_route('projects.show', ['project' => $project['slug']]) }}">…</a>
```

### Ajouter une troisième langue

1. Ajouter l'entrée dans `config/portfolio.php` → `locales` et ses segments dans `route_slugs`.
2. Dupliquer `lang/fr/` vers `lang/<code>/` et traduire.
3. Ajouter le `slug` de chaque projet pour cette langue dans `config/portfolio.php` → `projects`.

Les routes, le sélecteur de langue, les balises `hreflang` et le plan de site suivent automatiquement.

---

## Design system

Tout est défini en CSS, sans fichier `tailwind.config.js` (syntaxe CSS-first de Tailwind v4) :

- **Jetons de couleur, police et rayons** : `resources/css/app.css`, bloc `@theme`.
- **Composants** (verre dépoli, boutons, champs, trame) : même fichier, bloc `@layer components`.
- **Animations** et respect de `prefers-reduced-motion` : fin du même fichier.

Palette :

| Rôle | Valeur |
| --- | --- |
| Or primaire | `#F5C518` |
| Or secondaire (accents doux) | `#FFDE6B` |
| Or foncé (états actifs) | `#D4A50C` |
| Fond | `#070A11` · surfaces `#0D1220` / `#111829` |
| Contrepoint froid (valeurs « avant ») | `#4A6585` |
| Texte | `#F2F5FA` · secondaire `#A3AEC2` · atténué `#78839A` |

L'or est réservé aux accents, aux chiffres et aux appels à l'action : jamais au texte courant.
Tous les couples texte / fond dépassent le rapport de contraste 4,5:1 exigé par le niveau AA.

Composants Blade réutilisables, dans `resources/views/components/` :

```
ui/glass      panneau en verre dépoli (3 rayons : panel, tile, field)
ui/button     bouton or, verre ou discret, en <a> ou <button>
ui/badge      pastille (catégorie, outil)
ui/section    section avec titre, repère et chapô
ui/heading    repère + titre + chapô, utilisable seul
ui/metric     chiffre animé avec libellé
ui/gauge      jauge segmentée (niveau sur 5)
icon          tracés SVG en ligne (bibliothèque Lucide)
load-curve    courbe de charge animée du hero
project-card  carte de projet (deux variantes)
contact-form  formulaire et ses protections
layout/*      en-tête, pied de page, rail de navigation, décor, sélecteur de langue
sections/*    les huit sections de la page d'accueil
```

---

## Sécurité du formulaire de contact

Cinq protections se cumulent, du moins intrusif au plus visible :

| Protection | Mise en œuvre |
| --- | --- |
| Jeton CSRF | `@csrf`, vérifié par le groupe de middlewares `web` |
| Honeypot | Champ `website` hors écran et hors tabulation ; rempli ⇒ rejet (`app/Http/Requests/ContactRequest.php`) |
| Délai minimum | Jeton chiffré datant l'affichage du formulaire ; soumission en moins de 3 secondes ⇒ rejet. Formulaire de plus de 2 heures ⇒ rejet |
| Limiteur de débit | 3 envois par minute et 20 par jour et par adresse IP (`throttle:contact`) |
| Cloudflare Turnstile | Captcha invisible, actif dès que les clés sont renseignées |

Les durées sont réglables par `CONTACT_MIN_DELAY` et `CONTACT_MAX_AGE` dans `.env`.

### Activer Turnstile

1. Créer un site sur [le tableau de bord Cloudflare](https://dash.cloudflare.com/?to=/:account/turnstile)
   (gratuit), en mode **Invisible** ou **Managed**.
2. Renseigner les deux clés dans `.env` :

```dotenv
TURNSTILE_SITE_KEY=0x4AAAAAAA…
TURNSTILE_SECRET_KEY=0x4AAAAAAA…
```

Sans ces clés, le widget n'est pas rendu et la règle de validation n'est pas appliquée : le
formulaire reste utilisable en développement, protégé par les quatre autres couches.

### Messages reçus

Chaque demande est enregistrée dans la table `contact_messages` **avant** l'envoi du courriel :
une panne du serveur de messagerie ne fait jamais perdre une demande. Pour les relire :

```bash
php artisan tinker --execute 'App\Models\ContactMessage::latest()->get(["name","email","subject","created_at"])->each(fn ($m) => print($m->created_at." · ".$m->name." · ".$m->email.PHP_EOL));'
```

---

## En-têtes de sécurité

`app/Http/Middleware/SecurityHeaders.php` applique à chaque réponse une politique de sécurité du
contenu (CSP), `X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`, `Permissions-Policy`,
les en-têtes `Cross-Origin-*`, et `Strict-Transport-Security` dès que la connexion est en HTTPS.

La CSP n'autorise que ce dont le site a besoin : les scripts du domaine signés par un nonce, les
polices et images locales, et Cloudflare Turnstile. Deux assouplissements sont assumés et commentés
dans le fichier :

- `'unsafe-eval'` pour les scripts, parce qu'Alpine.js compile ses expressions à l'exécution. Les
  scripts injectés restent bloqués, faute de nonce valide.
- `'unsafe-inline'` pour les styles, parce que les attributs `style=` du rendu (jauges, délais
  d'animation) comptent comme des styles en ligne.

Le limiteur `throttle:public` (120 requêtes par minute et par IP) freine l'aspiration du site tout en
restant très au-dessus du rythme d'un robot d'indexation légitime.

---

## Référencement

- Titre et description propres à chaque page **et à chaque langue** (`lang/*/seo.php`).
- URL canonique, balises `hreflang` réciproques et `x-default` sur toutes les pages.
- Open Graph et Twitter Card, avec image de partage dédiée.
- Données structurées Schema.org : `Person`, `ProfessionalService`, `WebSite`, et `BreadcrumbList`
  sur les pages intérieures.
- Plan de site généré à la volée, avec les équivalences de langue : `/sitemap.xml`.
- `robots.txt` généré, pointant vers le plan de site : `/robots.txt`.
- Balises sémantiques HTML5, images en WebP avec dimensions explicites et chargement différé,
  lien d'évitement, navigation au clavier, `prefers-reduced-motion` respecté.

---

## Tests

```bash
php artisan test              # l'ensemble de la suite
php artisan test --compact    # sortie condensée
vendor/bin/pint               # formatage du code PHP
```

46 tests couvrent le rendu des pages, la négociation de langue, les URL localisées, les fiches
projet, le référencement, les en-têtes de sécurité et les cinq protections du formulaire de contact.

---

## Mise en production

```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build

php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

À vérifier avant d'ouvrir le site :

- `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` sur le domaine réel (les URL canoniques, le plan
  de site et les balises `hreflang` en découlent).
- `APP_KEY` renseignée : elle chiffre le jeton du formulaire et les cookies.
- Variables `MAIL_*` configurées, et `CONTACT_RECIPIENT` à la bonne adresse.
- Clés Turnstile renseignées.
- La racine web du serveur doit pointer sur `public/`, jamais sur la racine du projet.
- Derrière Cloudflare ou un répartiteur de charge, renseigner `TRUSTED_PROXIES` (`*` ou une
  liste d'adresses) pour que Laravel voie le schéma et l'adresse IP réels du visiteur : sans
  cela les URL canoniques restent en `http://` et le limiteur de débit compte tous les
  visiteurs comme une seule adresse.

Pour passer à MySQL ou PostgreSQL, renseigner les variables `DB_*` puis relancer `php artisan migrate`.

---

## Déploiement sur Render

Le projet est prêt à être déployé en conteneur : `Dockerfile` (Nginx + PHP-FPM), `render.yaml`
(service web + base PostgreSQL managée) et les configurations dans `docker/`.

La procédure complète — dépôt GitHub, création du Blueprint, variables à renseigner, vérifications
après mise en ligne, redéploiements — est dans **[DEPLOY.md](DEPLOY.md)**.

---

## À remplacer avant la mise en ligne

Le site est livré avec un **contenu de démonstration crédible mais fictif**. À remplacer :

- [ ] `config/portfolio.php` → `identity` : courriel, téléphone, ville, adresse LinkedIn.
- [ ] `config/portfolio.php` → `projects` et `lang/*/projects.php` : les six projets.
- [ ] `config/portfolio.php` → `timeline` et `lang/*/timeline.php` : le parcours, avec les vrais
      employeurs et établissements.
- [ ] `lang/*/testimonials.php` : les témoignages, ou `sections.testimonials => false` en attendant.
- [ ] `lang/*/legal.php` : statut, numéro d'immatriculation, hébergeur.
- [ ] `public/images/` : la photo professionnelle et les visuels des projets. Les images livrées sont
      des compositions abstraites générées pour l'occasion.
- [ ] `public/images/og-default.png` : la carte de partage, à régénérer avec la police
      Plus Jakarta Sans (celle livrée utilise une police système de substitution).

---

## Note sur les dépendances

`laravel/boost` est installé en dépendance de développement : il fournit les consignes de
`CLAUDE.md` / `AGENTS.md` et un serveur MCP destinés aux assistants de code. Il ne sert pas au site
et peut être retiré sans conséquence :

```bash
composer remove laravel/boost --dev
rm -f CLAUDE.md AGENTS.md .mcp.json
rm -rf .claude/skills
```
