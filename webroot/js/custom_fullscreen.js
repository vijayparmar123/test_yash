function DoFullScreen() {

    var isInFullScreen = (document.fullScreenElement && document.fullScreenElement !==     null) ||    // alternative standard method  
            (document.mozFullScreen || document.webkitIsFullScreen);

    var docElm = document.documentElement;
    if (!isInFullScreen) {

        if (docElm.requestFullscreen) {
            docElm.requestFullscreen();
        }
        else if (docElm.mozRequestFullScreen) {
            docElm.mozRequestFullScreen();
            /* alert("Mozilla entering fullscreen!"); */
        }
        else if (docElm.webkitRequestFullScreen) {
            docElm.webkitRequestFullScreen();
            /* alert("Webkit entering fullscreen!"); */
        }
    }
}