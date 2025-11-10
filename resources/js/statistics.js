$(document).ready(function ($) {
    loadPowerSlider();
    $(window).resize(function() {
        loadPowerSlider();
    });
});

function loadPowerSlider() {
    if (window.slider) window.slider.destroy();
    window.slider = new PowerSlider(
        '#slider-statistics',
        {min:1,max:maxMonth},
        [minVal,maxVal],
        1,
        true,
        true
    );
}