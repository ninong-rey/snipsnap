<?php
class Human{
        var $eyeColor;
        var $race;
        var $skinColor;

        public function __construct($eyeColor = 'brown', $race = 'asian', $skinColor = 'fair'){
            echo "I am human. ";
            $this->eyeColor = $eyeColor;
            $this->race = $race;
            $this->skinColor = $skinColor;
    
        }
        
        function displayEyeColor(){
            echo $this->eyeColor;
        }

        function displayRace(){
            echo $this->race;
        }

        function displaySkinColor(){
            echo $this->skinColor;
        }

       
    }


    class Person extends Human{
        var $country;

    public function __construct($eyeColor = 'bron', $race = 'asian', $skinColor = 'fair', $country = 'ph'){
            echo "I am human. ";
            $this->eyeColor = $eyeColor;
            $this->race = $race;
            $this->skinColor = $skinColor;
            $this->country = $country;
    }
    function displayCountry(){
        echo $this->country;
    }
}
    $obja = new Human();
    echo 'My eyed are color ';
    echo $obja->displayEyeColor().'.';
    echo 'I am ';
    echo $obja->displayRace().' and the color of my skin is ';
    echo $obja->displaySkinColor().'.';
    echo '<br>';

     $objb = new Human('blue ',' caucasian ','white ');
    echo 'My eyed are color ';
    echo $objb->displayEyeColor().'.';
    echo 'I am ';
    echo $objb->displayRace().' and the color of my skin is ';
    echo $objb->displaySkinColor().'.';
    echo '<br>';

    $objc = new Person('blue ',' caucasian ','white ');
    echo 'My eyed are color ';
    echo $objc->displayEyeColor().'.';
    echo 'I am ';
    echo $objc->displayCountry().'.';
    echo $objc->displayRace().' and the color of my skin is ';
    echo $objc->displaySkinColor().'.';
    echo '<br>';

    $objd = new Person('blue ',' caucasian ','white ','usa');
    echo 'My eyed are color ';
    echo $objd->displayEyeColor().'.';
    echo 'I am ';
    echo $objd->displayCountry().'.';
    echo $objd->displayRace().' and the color of my skin is ';
    echo $objd->displaySkinColor().'.';
    echo '<br>';

    
?>