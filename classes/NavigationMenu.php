<?php

class NavigationMenu
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

        // much more to be added

        if(User::isLoggedIn())
        {
            $menuHtml .= $this->createNavItem("Settings", "static/img/icons/settings.png", "settings.php");
            $menuHtml .= $this->createNavItem("Log out", "static/img/icons/logout.png", "logout.php");
            $menuHtml .= $this->createSubscriptionsSection();
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

    private function createSubscriptionsSection()
    {
        // will show up if the user is logged in and has some channel subscribed

        $subscriptions = $this->userLoggedInObj->getSubscriptions();

        $html = "<span class='heading'>Subscriptions</span>";
        foreach($subscriptions as $sub)
        {
            $subUsername = $sub->getUsername();
            $html .= $this->createNavItem($subUsername, $sub->getAvatar(), "profile.php?username=$subUsername");
        }
        return $html;
    }
}

?>