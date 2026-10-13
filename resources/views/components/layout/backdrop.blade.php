{{-- Décor de fond : trame d'étude technique et deux halos. Un seul halo doré par écran. --}}
<div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden bg-base" aria-hidden="true">
    <div class="plot-grid absolute inset-x-0 top-0 h-[150vh]"></div>

    <div
        class="gold-bloom bloom-breathe absolute -top-56 right-[-12%] h-[42rem] w-[42rem] rounded-full"
        data-parallax="0.05"
    ></div>

    <div
        class="steel-bloom absolute bottom-[-25%] left-[-18%] h-[36rem] w-[36rem] rounded-full opacity-50"
        data-parallax="-0.02"
    ></div>

    {{-- Assombrissement du bas de page : le contenu garde le dessus sur le décor. --}}
    <div class="absolute inset-x-0 bottom-0 h-72 bg-gradient-to-t from-base-deep to-transparent"></div>
</div>
