<?php

class NavigationMenuSmall
{
    private $con, $userLoggedInObj;

    public function __construct($con, $userLoggedInObj)
    {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function create()
    {
        $menuHtml = $this->createNavItem("Home", "static/img/icons/home.png", "index.php");
        $menuHtml .= $this->createNavItem("Trending", "static/img/icons/trending.png", "trending.php");
        $menuHtml .= $this->createNavItem("Subscriptions", "static/img/icons/subscriptions.png", "subscriptions.php");
        $menuHtml .= $this->createNavItem("Liked videos", "static/img/icons/thumb-up.png", "likedVideos.php");

        if(User::isLoggedIn())
        {
            $menuHtml .= $this->createNavItem("Settings", "static/img/icons/settings.png", "settings.php");
            
        }

        return "<div class='navigationItems'>
                    $menuHtml
                </div>";
    }
    
    private function createNavItem($text, $icon, $link)
    {
        return "<div class='navigationItem'>
                    <a href='$link'>
                        <img src='$icon'>
                        <span>$text</span>
                    </a>
                </div>";
    }
}

?>