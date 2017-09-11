var files = ["..\/images\/leaflet\/football\/slideshow\/0000000000000000000000000000000000.jpg","..\/images\/leaflet\/football\/slideshow\/20403740_1114381945329551_1864349788_n.jpg","..\/images\/leaflet\/football\/slideshow\/20427173_1114388448662234_883674683_o.jpg","..\/images\/leaflet\/football\/slideshow\/20496532_1114381898662889_1733044866_n.jpg","..\/images\/leaflet\/football\/slideshow\/20503787_1114381985329547_499884642_n.jpg","..\/images\/leaflet\/football\/slideshow\/20504170_1114381918662887_326041608_n.jpg","..\/images\/leaflet\/football\/slideshow\/20524467_1114387758662303_1237932208_o.jpg","..\/images\/leaflet\/football\/slideshow\/20526397_1114381921996220_1916863868_n.jpg","..\/images\/leaflet\/football\/slideshow\/20526431_1114381961996216_446743077_n.jpg","..\/images\/leaflet\/football\/slideshow\/20526460_1114381955329550_474103275_n.jpg","..\/images\/leaflet\/football\/slideshow\/20526571_1114381981996214_1946866164_n.jpg","..\/images\/leaflet\/football\/slideshow\/20526700_1114381941996218_1615201612_n.jpg","..\/images\/leaflet\/football\/slideshow\/20527337_1114381965329549_231454038_n.jpg","..\/images\/leaflet\/football\/slideshow\/20527435_1114381968662882_1854301463_n.jpg","..\/images\/leaflet\/football\/slideshow\/20527444_1114374405330305_615881216_n.jpg","..\/images\/leaflet\/football\/slideshow\/20527520_1114381951996217_190344425_n.jpg","..\/images\/leaflet\/football\/slideshow\/20536118_1114388478662231_402331591_o.jpg","..\/images\/leaflet\/football\/slideshow\/20536253_1114388108662268_60028155_o.jpg","..\/images\/leaflet\/football\/slideshow\/20542998_1114388118662267_507013736_o.jpg","..\/images\/leaflet\/football\/slideshow\/20561660_1114381948662884_1996504012_n.jpg","..\/images\/leaflet\/football\/slideshow\/20561663_1114381911996221_1849869183_n.jpg","..\/images\/leaflet\/football\/slideshow\/20561724_1114381908662888_232895760_n.jpg","..\/images\/leaflet\/football\/slideshow\/20561743_1114381915329554_274723609_n.jpg","..\/images\/leaflet\/football\/slideshow\/20562656_1114381895329556_240921432_n.jpg","..\/images\/leaflet\/football\/slideshow\/20562831_1114381901996222_164283219_n.jpg","..\/images\/leaflet\/football\/slideshow\/20590595_1114388061995606_1488909998_o.jpg","..\/images\/leaflet\/football\/slideshow\/20590614_1114387768662302_1125732714_o.jpg","..\/images\/leaflet\/football\/slideshow\/20590858_1114388425328903_1832757774_o.jpg","..\/images\/leaflet\/football\/slideshow\/20597555_1114388441995568_291814333_o.jpg","..\/images\/leaflet\/football\/slideshow\/20614187_1114381958662883_1250436784_n.jpg","..\/images\/leaflet\/football\/slideshow\/20614292_1114381905329555_1610821727_n.jpg","..\/images\/leaflet\/football\/slideshow\/20615121_1114388431995569_1469184180_o.jpg"];
var currentIndex = 0;
var isSlideshowPlaying = true;
var autoSlideshow;

function startSlideshow() {
    autoSlideshow = setInterval(function() {
        nextPhoto(1);
    }, 5000);
}

startSlideshow();

function nextPhoto(skip){
    currentIndex += skip;

    if(currentIndex < 0){
        currentIndex = files.length + currentIndex;
    }

    if(currentIndex >= files.length){
        currentIndex = currentIndex - files.length;
    }

    var currentFile = files[currentIndex];
    $("#slideshow img").attr("src", currentFile);

    if (isSlideshowPlaying) {
        // reset timer
        clearInterval(autoSlideshow);
        startSlideshow();
    }
}

function slideshowPausePlay(action, button) {
    if (action === 0) {
        clearInterval(autoSlideshow);
        isSlideshowPlaying = false;
        button.onclick = function() { slideshowPausePlay(1, this) };
        button.classList.add("toggledSlideshowButton");
    } else if (action === 1) {
        clearInterval(autoSlideshow);
        startSlideshow();
        isSlideshowPlaying = true;
        button.onclick = function() { slideshowPausePlay(0, this) };
        button.classList.remove("toggledSlideshowButton");
    }
}