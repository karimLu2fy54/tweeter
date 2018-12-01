<?php
namespace tweeterapp\view;

use tweeterapp\model\User;
use mf\router\Router;



class TweeterView extends \mf\view\AbstractView {
  
    /* Constructeur 
    *
    * Appelle le constructeur de la classe parent
    */
    public function __construct( $data ){
        parent::__construct($data);
   
    }



    /* MÃ©thode renderHeader
     *
     *  Retourne le fragment HTML de l'entÃªte (unique pour toutes les vues)
     */ 
    public function renderHeader(){

        $router = new Router();

        $renderHeader = '<header>';

        $renderHeader .= '<h1>MiniTweeTR by KK</h1>';

        $renderHeader .= '</header>';

        // si l'utilisateur est connecté on à le droit à d'autres options : ajouter un tweet, voir les gens qui se suivent, les utilisateurs classés seulon leu nombre de followers
        if(isset($_SESSION['user_login'])){

        	$renderHeader .= "<nav> <a href='".$router->urlFor('home')."'> <img src='https://img.utdstc.com/icons/htc-home.png:s' align='left' > </a> <a href='".$router->urlFor('logout')."'> <img src='https://img.icons8.com/ios/2x/export.png' align='center' > </a> </a> <a href='".$router->urlFor('signUp')."'> <img src='https://www.stimey-dev.eu/img/before-login/signup.png' align='center' > </a> <br/> <br/>  </nav> <p> ".$_SESSION['user_login']." : vous etes connecté </p>  <a href='".$router->urlFor('ViewFollowers')."'> <img src='https://site-images.similarcdn.com/url?url=https%3A%2F%2Fis1-ssl.mzstatic.com%2Fimage%2Fthumb%2FPurple128%2Fv4%2F88%2F70%2F40%2F88704050-3b73-2503-2f90-9d362868cb46%2Fsource%2F100x100bb.png&h=fcbaa440579242a2146d5abb647c7c9c282a862a38d4245d3c366373de92a7c1' align='left' > </a>
        	<a href='".$router->urlFor('post')."'> <img src='https://www.textmunication.com/wp-content/uploads/2018/06/brdf.png' align='center' > </a> <a href='".$router->urlFor('ViewNumbersOfFollowersOfUsers')."'> <img src='http://lh6.ggpht.com/FuISCmHUAbmdtN1moJGETB75mXC4eTFj9SL4FMzA5Nd4xvO-Fgx6bc-0Z4RM3hiAcA=w100' align='right' > </a>";

        } else {

        	//autrement l'utilisateur anonyme n'aura droit qu'aux options de base : aller dans le home, s'authentifier, ou s'inscrire s'il n'a pas déja un compte 
        	$renderHeader .= "<nav> <a href='".$router->urlFor('home')."'> <img src='https://img.utdstc.com/icons/htc-home.png:s' align='left' > </a> <a href='".$router->urlFor('signIn')."'> <img src='https://www.cardaccess.com.au/wp-content/uploads/2014/11/lock-icon-.png' align='center' > </a> </a> <a href='".$router->urlFor('signUp')."'> <img src='https://www.stimey-dev.eu/img/before-login/signup.png' align='center' > </a> </br>   </nav> <p> vous n etes pas connecté </p>";

        }
        

        return $renderHeader;
    
  }
    /* MÃ©thode renderFooter
     *
     * Retourne le fragment HTML du bas de la page (unique pour toutes les vues)
     */
    public function renderFooter(){
        return '<div class="tweet-footer"> La super app crÃ©Ã©e en Licence Pro &copy;2018 </div>';
    }

    /* MÃ©thode renderHome
     *
     * Vue de la fonctionalitÃ© afficher tous les Tweets. 
     *  
     */
    
    public function renderHome(){

        


        $router = new \mf\router\Router();        

        $res = "<article class='theme-backcolor2'>";
        $res .= "<h2>Latest Tweets</h2>";
        foreach($this->data as $key => $t){
            $user = User::select()->where('id','=',$t->author)->first();
            //$user = User::select()->where('id','=',$t->author)->get();

            $res .= "<div class='tweet'>";
            $res .= "<a href='".$router->urlFor('view',['id' => $t->id])."' class='tweet-text'>$t->text</a>";
            //$res .= "<a href='".$router->urlFor('user',['id' => $user->id])."' class='tweet-author'>$user->username</a>";

            $res .= "<a href='".$router->urlFor('profil',['id_user' => $t->author])."' class='tweet-text'> <p class='tweet-author'>$user->username</p> </a>";
           
            //$res .= $t->author;
            $res .= "<p>$t->created_at</p>";

            // si l'utilisateur est authentifié, il pourra liker ou non des profils
            if(isset($_SESSION['user_login'])){
            $res .= "<p class='tweet-text'> <a href='".$router->urlFor('like',['id' => $t->id])."'> <img src='http://localhost/tp/tp_php/TP_tweeter/images/like.png' align='left' > </a> &nbsp &nbsp &nbsp &nbsp &nbsp score : $t->score <a href='".$router->urlFor('dislike',['id' => $t->id])."'> <img src='http://localhost/tp/tp_php/TP_tweeter/images/dislike.png' align='right' > </a></p>";
        }

            $res .= "</div>";
        }
        $res .= "</article>";
        
        return $res;
        
        
    }


    //affichage du profil d'un utilisateur en cliquant sur l'auteur d'un tweet
    public function renderProfil(){

    $router = new \mf\router\Router();

    $res = "";

    foreach($this->data as $key => $t){
    $res .= "<div class='tweet2'>";
    		$res .= "<h1> voila le profil </h1> </br>";
            $res .= "fullname :".$t->fullname;
            $res .= "<a href='".$router->urlFor('user',['id_user' => $t->id])."' class='tweet-text'>username :".$t->username." voir les twwets de cet utilisateur</a>";
            $res .= "nombre de followers : ".$t->followers;

            $res .= "</br> </br>";

            /*
            $res .= "<a href='".$router->urlFor('follow')."'> <img src='https://image.spreadshirtmedia.net/image-server/v1/mp/compositions/T813A645MPA1671PT17X22Y57D141038603S215Cx000000/views/1,width=100,height=100,appearanceId=645,backgroundColor=7B8199,noPt=true,version=1521206251/follow-me-t-shirt-premium-femme.jpg' align='center' > </a>";
            */
   
            $res .= "</div>";
                 
    }
        return $res;

        	
    }
  
    /* MÃ©thode renderUeserTweets
     *
     * Vue de la fonctionalitÃ© afficher tout les Tweets d'un utilisateur donnÃ© en allant dans le profil de l'utilisateur en cliqaunt sur lire ses tweets 
     * 
     */
     
    public function renderUserTweets(){


       $router = new \mf\router\Router(); 

        $res = "";

         foreach($this->data as $key => $t){
       
            $res .= "<div class='tweet'>";
            $res .= "<a href='".$router->urlFor('view',['id' => $t->id])."' class='tweet-text'>$t->text</a>";
            //$res .= "<a href='".$router->urlFor('user',['id' => $user->id])."' class='tweet-author'>$user->username</a>";

            $res .= "</br>";

            $res .= "<a href='".$router->urlFor('user',['id_user' => $t->id])."' class='tweet-text'> $t->created_at </a>";
        
            $res .= "</div>";
                 }

        return $res;

        /* 
         * Retourne le fragment HTML pour afficher
         * tous les Tweets d'un utilisateur donnÃ©. 
         *  
         * L'attribut $this->data contient un objet User.
         *
         */
        
    }

    /*
    public function renderUserSortsByScoreASC(){

        $renderUserSortsByScoreASC .= '';



    }
    */
  
    /* MÃ©thode renderViewTweet 
     * 
     * RrÃ©alise la vue de la fonctionnalitÃ© affichage d'un tweet
     *
     */

    
    
    public function renderViewTweet(){

        
        $router = new \mf\router\Router(); 

         if(!is_null($this->data)){
            $user = User::select()->where('id','=',$this->data->author)->first();
            $res = "<article class='theme-backcolor2'>";
            $res .= "<div class='tweet'>"; 
                    $res .= "<a href='".$router->urlFor('view',['id' => $this->data->id])."' class='tweet-text'>".$this->data->text."</a>";
                    $res .= "<a href='".$router->urlFor('user',['id' => $user->id])."' class='tweet-author'>".$user->username."</a>";
                    $res .= "<p>".$this->data->created_at."</p>";
                    $res .= "<div class='tweet-footer'><hr>";
                    $res .= "<p class='tweet-score'>".$this->data->score."</p>";
                    $res .= "</div>";
            $res .= "</div>";
            $res .= "</article>";
                
            return $res;
         }else{
             return;
         }        
    }

    public function renderfollowingOfAnUser(){

         $router = new \mf\router\Router();  


        $renderfollowingOfAnUser = "";
        $renderfollowingOfAnUser .= "<article class='theme-backcolor2'>";
        $renderfollowingOfAnUser .= "<h2>Followers of an user</h2>";
        foreach($this->data as $key => $t){
            $user = Follow::select()->where('follower','=',$id);

            $renderfollowingOfAnUser .= "<div class='tweet'>";
            $renderfollowingOfAnUser .= "<div class='tweet-text'>$t->followee</div>";
           // $res .= "<a href='".$router->urlFor('user',['id' => $user->id])."' class='tweet-author'>$user->username</a>";
            $renderfollowingOfAnUser .= "</div>";
        }
        $renderfollowingOfAnUser .= "</article>";
        
        return $renderfollowingOfAnUser;

        $renderfollowingOfAnUser = "";

    }



    /* MÃ©thode renderPostTweet
     *
     * Realise la vue de rÃ©gider un Tweet
     *
     */
    public function renderPostTweet(){


        $router = new Router();

        $html = <<<EOF
<div id="tweet-form">
    <form action="{$router->urlFor('send')}" method="post">
        <label for="tweet">Rédigez votre tweet</label><br />
        <textarea name="tweet" rows="10" cols="50">trololo</textarea> </br>
        <button type='submit'> Poster le tweet <button>
    </form>
</section>
EOF;

       

         return $html;

        
        /* MÃ©thode renderPostTweet
         *
         * Retourne la framgment HTML qui dessine un formulaire pour la rÃ©daction 
         * d'un tweet, l'action du formulaire est la route "send"
         *
         */
        
    }

    public function renderSendTweet(){


        $renderPostTweet = '';

        $renderPostTweet .= "<div class='tweet'>";

        
        $renderPostTweet .= "votre tweet a été posté";


        $renderPostTweet .= '</div>';

 

         return $renderPostTweet;
        
    }

    //affichage du formulaire qui permettra de s'enregistrer en tant que nouvel utilisateur

    private function renderViewFormSignUp(){

    	$router = new Router();

    	$res = "<section id='create'>";

        $html = <<<EOF
<section id="create">
    <form action="{$router->urlFor('signup_check')}" method="post">
        <div><label for="">Fullname</label> <input type="text" name="fullname"></div>
        <div><label for="">Username</label> <input type="text" name="username"></div>
        <div><label for="">Password</label> <input type="password" name="password"></div>
        <div><label for="">Password Confirmation</label> <input type="password" name="password_confirm"></div>
        <button type="submt">Register</button>
    </form>
</section>
EOF;

        return $html;
    }

    //formulaire pour se connecter

    private function renderViewFormSignIn(){

    	$router = new Router();

    	$res = <<<EOF

    	<form action="{$router->urlFor('login_check')}" method="post">        
        <input class="input" type="text" name="username" id="username" placeholder="username" required /><br>
        <input class="input" type="password" name="password" id="password" placeholder="password" required /><br>

        <button type='submit'>Se connecter</button>
        </form>

EOF;

        return $res;
    }

    

    //vue pour voir les relation de following entre utilisateurs
    private function renderViewFollowers(){

        $renderViewNumbersOfFollowersOfUsers = "";

        $renderViewNumbersOfFollowersOfUsers .= "voici les followers";

        foreach($this->data as $key => $t){
            $user = User::select()->where('id','=',$t->follower)->first();
            $user2 = User::select()->where('id','=',$t->followee)->first();


            $renderViewNumbersOfFollowersOfUsers .= "<div class='tweet'>";
            $renderViewNumbersOfFollowersOfUsers .= "<div class='tweet-text'>$user->username</div>";
            $renderViewNumbersOfFollowersOfUsers .= "<div class='tweet-text'> suit </div>";
            $renderViewNumbersOfFollowersOfUsers .= "<div class='tweet-text'>$user2->username</div>";
           // $res .= "<a href='".$router->urlFor('user',['id' => $user->id])."' class='tweet-author'>$user->username</a>";
            $renderViewNumbersOfFollowersOfUsers .= "</div>";
        }

        return $renderViewNumbersOfFollowersOfUsers;

    }


    //vue avec le classement des utilisateur suivant leur nombre de followers
    private function renderViewNumbersOfFollowersOfUsers(){

        $render = "";

        $render .= "voici la liste des utilisateurs triée selon leur nombre de followers";

        foreach($this->data as $key => $t){
            
            $render .= "<div class='tweet'>";
            $render .= "<div class='tweet-text'>$t->username</div>";
            $render .= "<div class='tweet-text'> a pour score </div>";
            $render .= "<div class='tweet-text'>$t->followers</div>";
           // $res .= "<a href='".$router->urlFor('user',['id' => $user->id])."' class='tweet-author'>$user->username</a>";
            $render .= "</div>";
        }

        return $render;

    }


    /* MÃ©thode renderBody
     *
     * Retourne la framgment HTML de la balise <body> elle est appelÃ©e
     * par la mÃ©thode hÃ©ritÃ©e render.
     *
     */
    //cette méthode va appeler les diffèrentes méthodes d'affichages suivant l'utilisation. L'appel se fera à partir du tweetercontroler ou tweeteradmincontroler pour les authentification et/ou inscriptions.

    public function renderBody($selector){


        $http_req = new \mf\utils\HttpRequest();

        $rendu = "";

        $rendu .= "<header class='theme-backcolor1'>".$this->renderHeader();

        $rendu .= "<nav id='nav-menu'>";


        $rendu .= "</nav></header>";

        $rendu .= "<section>";

        if($selector == 'home'){

        	//$rendu .= "vous etes dans le home";
            $rendu .= $this->renderHome();

        }else if($selector == 'userTweets'){



            $rendu .= $this->renderUserTweets();

        }else if($selector == 'viewTweet'){

            $rendu .= $this->renderViewTweet();

        }else if($selector == 'PostTweet'){

            $rendu .= $this->renderPostTweet();

        }else if($selector == 'SendTweet'){

            $rendu .= $this->renderSendTweet();

        }else if($selector == 'viewFormSignIn'){

            $rendu .= $this->renderViewFormSignIn();
            //viewFormSignUp

        }else if($selector == 'viewFormSignUp'){

            $rendu .= $this->renderViewFormSignUp();
            //viewFormSignUp

        }else if($selector == 'viewFollowers'){

            $rendu .= $this->renderViewFollowers();
            
            //viewFormSignUp

        }else if($selector == 'viewNumbersOfFollowersOfUsers'){

            $rendu .= $this->renderViewNumbersOfFollowersOfUsers();
            

        }else if($selector == 'profil'){

        	$rendu .= $this->renderProfil();
        }

        	//ViewNumbersOfFollowersOfUsers
            
        

        $rendu .= $this->renderFooter();

        return $rendu;
        
    }

    //methode qui va afficher la page à partir du renderbody
    public function render($selector){
        /* le titre du document */
        $title = self::$app_title;

        /* les feuilles de style */
        
        $app_root = (new \mf\utils\HttpRequest())->root;
        $styles_sheets = 'html/styleTweeter.css';
        $styles ='';
        foreach ( self::$style_sheets as $file )
            $styles .= '<link rel="stylesheet" href="'.$app_root.'/'.$file.'"> ';
        
        
        
        /* on appele la methode renderBody de la sous classe */
        $body = $this->renderBody($selector);
        

        /* construire la structure de la page 
         * 
         *  Noter l'utilisation des variables ${title} ${style} et ${body}
         * 
         */
/*              
        $html = <<<EOT
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>${title}</title>
	    ${styles}
    </head>

    <body>
        
       ${body}
 <link rel="stylesheet" type="text/css" href="html/styleTweeter.css">
    </body>
</html>
EOT;
*/
$html = <<<EOT
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>${title}</title>
        ${styles}
    </head>

    <body>
        
       ${body}

    </body>
</html>
EOT;

        /* Affichage de la page 
         *
         * C'est la seule instruction echo dans toute l'application 
         */
        
        echo $html;
    }

    

}