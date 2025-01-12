let toggleBg = false

function changeBackground(hexNumber)
{
    if (toggleBg){
        toggleBg = false
    }   else {
        toggleBg = true
    }

    if (toggleBg){
        document.body.style.background = hexNumber;

    }else {
        document.body.style.background = "#fff";

    }
}