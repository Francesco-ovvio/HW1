<nav>
                <!--NavBar Desktop-->
                <div class="menu" name="sx">
                    <a class="button" href="index.php">HomePage</a>
                    <a class="button" href="#">Cereali</a>
                    <a class="button" href="#">Latticini</a>
                    <a class="button" href="#">About</a>
                </div>

                <div class="dx">
                    
                    <div class="menu">
                        <?php include 'php/checkLog.php';?>
                    </div>
                    <div class="logopicc">
                        <img src="https://i.imgur.com/muJjK01.png">
                    </div>
                </div>

                <!--NavBar Mobile v2-->
                <div id="mobile">
                    <div id="MySidenav" class="sidenav">
                        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                        <?php
                            if(isset($_SESSION['username'])){
                                echo "<h1><br><br>Benvenuto ".$_SESSION['username']."</h1>";
                                echo "<a href='profile.php'>Profilo</a>";
                                echo "<a href='cart.php'>Carrello</a>";
                                echo "<a href='mhw3.php'>Shop</a>";
                                echo "<a href='php/logout.php'>Logout</a>";
                            }else{
                                echo "<a href='login.php'>Login</a>";
                            }
                        ?>
                        <a href="index.php">HomePage</a>
                        <a href="#">Cereali</a>
                        <a href="#">Latticini</a>
                        <a href="#">About</a>
                    </div>
                    <span id="burger" onclick="openNav()">&#9776; Menu</span>
                </div>
            </nav>