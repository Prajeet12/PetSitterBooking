<?php 
include('../client/navbar_default.php');
// Include your database configuration file to establish a connection
include('../includes/config.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link rel="stylesheet" href="../client/styles.css">
</head>

<body>


    <!-- About Start -->
    <div class="about-section">
        <div class="about-container">
            <div class="about-row">
                <div class="about-col-img">
                    <div class="about-img-wrapper">
                        <img src="img/about.jpg" class="about-img">
                    </div>
                </div>
                <div class="about-col-text">
                    <div class="about-text-wrapper">
                        <h2 class="about-subtitle">About Us</h2>
                        <h1 class="about-title">We Keep Your Pets Happy All Time</h1>
                    </div>
                    <h4 class="about-description">Diam dolor diam ipsum tempor sit. Clita erat ipsum et lorem stet no
                        labore
                        lorem sit clita duo justo magna dolore</h4>
                    <div class="about-content">
                        <div class="tab-list">
                            <button class="tablinks" onclick="openCity(event, 'Mission')"
                                id="defaultOpen">Mission</button>
                            <button class="tablinks" onclick="openCity(event, 'Vision')">Vission</button>
                        </div>

                        <div id="Mission" class="tabcontent">

                            <p>Tempor erat elitr at rebum at at clita aliquyam consetetur. Diam dolor diam ipsum et,
                                tempor voluptua sit consetetur sit. Aliquyam diam amet diam et eos sadipscing labore.
                                Clita erat ipsum et lorem et sit, sed stet no labore lorem sit. Sanctus clita duo justo
                                et tempor consetetur takimata eirmod, dolores takimata consetetur invidunt magna dolores
                                aliquyam dolores dolore. Amet erat amet et magna</p>
                        </div>

                        <div id="Paris" class="tabcontent">

                            <p>Tempor erat elitr at rebum at at clita aliquyam consetetur. Diam dolor diam ipsum et,
                                tempor voluptua sit consetetur sit. Aliquyam diam amet diam et eos sadipscing labore.
                                Clita erat ipsum et lorem et sit, sed </p>
                        </div>

                        <div id="Tokyo" class="tabcontent">
                            <h3>Tokyo</h3>
                            <p>Tokyo is the capital of Japan.</p>
                        </div>

                        <script>
                        function openCity(evt, cityName) {
                            var i, tabcontent, tablinks;
                            tabcontent = document.getElementsByClassName("tabcontent");
                            for (i = 0; i < tabcontent.length; i++) {
                                tabcontent[i].style.display = "none";
                            }
                            tablinks = document.getElementsByClassName("tablinks");
                            for (i = 0; i < tablinks.length; i++) {
                                tablinks[i].className = tablinks[i].className.replace(" active", "");
                            }
                            document.getElementById(cityName).style.display = "block";
                            evt.currentTarget.className += " active";
                        }

                        // Get the element with id="defaultOpen" and click on it
                        document.getElementById("defaultOpen").click();
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- About End -->
</body>

</html>