<div id="cropEditor" class="hidden">

    <!-- Container -->
    <div class="mx-auto w-[750px] border border-gray-200 rounded-xl p-8 bg-gray-50">

    <!-- Instructions -->
    <p class="text-xs font-medium text-gray-400 uppercase tracking-widest mb-6 text-center">
        Drag to reposition · Scroll or slide to zoom
    </p>

    <!-- Canvas Wrapper -->
    <div id="canvasWrap"
            class="relative mx-auto cursor-grab active:cursor-grabbing select-none overflow-hidden rounded-lg bg-gray-200 shadow-sm"
            style="width:300px;height:300px;">

        <!-- Canvas where image is drawn -->
        <canvas id="cropCanvas" width="300" height="300"></canvas>

        <!-- Circular crop overlay -->
        <div class="absolute inset-0 pointer-events-none"
            style="border-radius:50%;border:2.5px solid #991b1b;"></div>
    </div>

    <!-- Zoom Controls -->
    <div class="flex flex-col items-center mt-8">
        <div class="flex items-center gap-4">

        <!-- Zoom Out -->
        <button type="button" id="zoomOut" class="w-8 h-8 border rounded-full">−</button>

        <!-- Zoom Slider -->
        <input type="range" id="zoomSlider" min="100" max="300" value="100"
                class="w-[340px] accent-red-800">

        <!-- Zoom In -->
        <button type="button" id="zoomIn" class="w-8 h-8 border rounded-full">+</button>
        </div>

        <!-- Zoom Percentage Display -->
        <span id="zoomPct" class="text-xs text-gray-400 mt-2">100%</span>
    </div>

    <!-- Action Buttons -->
    <div class="flex gap-4 mt-8 max-w-[500px] mx-auto">
        <button type="button" id="cropCancelBtn"
                class="flex-1 border rounded-lg py-2.5 text-sm">
        Cancel
        </button>

        <button type="button" id="cropApplyBtn"
                class="flex-1 bg-red-800 text-white rounded-lg py-2.5 text-sm">
        Apply crop
        </button>
    </div>
    </div>
</div>
