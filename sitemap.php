<!DOCTYPE html>
<html lang="en">

    <head>
        <?php include("head.php"); ?>
        <link rel="stylesheet" href="./CSS/styles.css" rel="stylesheet">
    </head>

    <body>

        <?php include("header.php"); ?>

        <!-- ################################################## MAIN CONTENT ################################################## -->
        <main>
            <div class="container">
                <!--                <div class="sitemap">-->
                <div class="bg-white rounded p-5 ">
                    <nav>
                        <ul>
                            <li><a href="index.php" target="_blank" class="sitemap">Home</a></li>
                            <li><a href="signup.php" target="_blank" class="sitemap">Sign Up</a></li><!-- Goes to the login??-->
                            <li><a href="login.php" target="_blank" class="sitemap">Login</a></li>

                            <!--*** NEED TO MAKE SURE THE EMPLOYEE GLOBAL VARIABLE IS SET AND STUFF BEFORE SHOWING THIS!!!  ***-->
                            <div id="empmaphide" >
                                <li><a href="employee.php" target="_blank" class="sitemap">Employee</a>
                                    <ul>
                                        <li><a href="employee.php" target="_blank" class="sitemap">Admin</a></li>
                                        <li><a href="employee.php" target="_blank" class="sitemap">Upload</a></li>
                                        <li><a href="employee.php" target="_blank" class="sitemap">Approver</a>
                                            <ul>
                                                <li><a href="employee.php" target="_blank" class="sitemap">Proofread</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="employee.php" target="_blank" class="sitemap">How To?</a></li>   
                                    </ul>
                                </li>
                            </div>

                            <!--*** NEED TO MAKE SURE THE VOLUNTEER GLOBAL VARIABLE IS SET AND STUFF BEFORE SHOWING THIS!!!  ***-->
                            <div id="volmaphide">
                                <li><a href="volunteer.php" target="_blank" class="sitemap">Volunteer</a>
                                    <ul>
                                        <li><a href="volunteer.php" target="_blank" class="sitemap">Transcription</a></li>
                                        <li><a href="volunteer.php" target="_blank" class="sitemap">How To?</a></li>
                                    </ul>
                                </li>
                            </div>

                            <li><a href="view-transcribed-documents.php" target="_blank" class="sitemap">View Transcribed Documents</a></li>
                            <li><a href="about-us.php" target="_blank" class="sitemap">About Us</a></li>
                            <li><a href="contact-us.php" target="_blank" class="sitemap">Contact</a></li>
                            <li><a href="review.php" target="_blank" class="sitemap">Leave Review</a></li>

                        </ul>
                    </nav>

                </div>

            </div>

        </main>

        <?php include("footer.php"); ?>

    </body>

</html>
