$(document).ready(function() {
    var main = $("#mainSectionContainer");
    var loc = window.location.href;
    if(loc == "http://localhost/foottube/index.php" || loc == "http://localhost/foottube/" || loc == "http://localhost/foottube/trending.php" || loc == "http://localhost/foottube/likedVideos.php")
    {
        main.addClass("leftPaddingSmall");
    }

    $(".navShowHide").on("click", function(){
        

        //grab the sidenav section

        var nav = $("#sideNavContainer");
        var navS = $("#sideNavContainerSmall");

        if(main.hasClass("leftPadding")) {
            nav.hide();
            navS.show();
            if(loc == "http://localhost/foottube/index.php" || loc == "http://localhost/foottube/" || loc == "http://localhost/foottube/trending.php" || loc == "http://localhost/foottube/likedVideos.php")
            {
                main.addClass("leftPaddingSmall");
            }
        }
        else
        {
            nav.show();
            navS.hide();
            main.removeClass("leftPaddingSmall");
        }


        // toggle the class for the main section

        main.toggleClass("leftPadding");
    })
})

function notSignIn()
{
	alert("You must be logged in to perform this action!");
}