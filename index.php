<!DOCTYPE html>
<html lang="en">

<head>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <?php include("head.php"); ?>
  <script type="text/javascript" src="./JS/index.js"></script>
  <link rel="stylesheet" href="./CSS/index.css" rel="stylesheet">
</head>

<body>

  <?php include("header.php"); ?>
  <div class="welcome">
    <img class="hero" src="IMAGES/museum.jpg" alt="fredericton region museum" />
    <div id="welcome_header">Welcome to the Fredericton Region Museum Digital Volunteer Project!</div>
  </div>

  <main>
    <div class="row" id="row__intro">
      <div class="col" id="col__intro">
        <p>Become a Digital Volunteer with the Fredericton Region Museum and help us bring the written history of Central New Brunswick to life!
          We have hundreds and thousands of pages of original archival letters, postcards and documents that are just waiting to be transcribed. We also have wills, deeds, birth, death and marriage certificates, diaries, marketing and promotional materials and even our own early Officers' Quarters magazine and newsletters.
          <BR /><BR /><span class="spanbold">These stories are just waiting to be revealed by you!</span>
        </p>
        <div class="scene">
          <div class="box_wrapper">
            <div class="front"><img style="width: 100%;" src="./IMAGES/icon-sign-up.jpg" alt="Sign up" /></div>
            <div class="back"><img style="width: 100%;" src="./IMAGES/icon-transcribe.jpg" alt="Transcribe" /></div>
            <div class="right"><img style="width: 100%;" src="./IMAGES/icon-proofread.jpg" alt="Proofread" /></div>
            <div class="left"><img style="width: 100%;" src="./IMAGES/icon-view-collections.jpg" alt="View Collections" /></div>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-2">
      <div class="col-6">
        <h2 id="startHeader" class="centerpad headercolor">Wondering how to get started? <span id="startArrow">&#x25BC;</span></h2>
        <div id="startContent" class="listmargin" style="display: none;">
          <ul class="outerlist">
            <li><a class="linktext" href="./signup.php" target="_blank">SIGN UP</a> to join the Fredericton Region Museum's Digital Volunteers and help us to bring history to life! You can then...</li>
            <ul class="innerlist">
              <li><a class="linktext" href="./login.php?trans" target="_blank">TRANSCRIBE</a> an original document in a specific collection, or;</li>
              <li><a class="linktext" href="./login.php?proof" target="_blank">PROOFREAD</a> documents that have been transcribed by someone else.</li>
            </ul>
            <li>Completed documents appear in the <a class="linktext" href="./view-transcribed-documents.php?coll=all&page=1&count=25" target="_blank">VIEW TRANSCRIBED DOCUMENTS</a> section. Take a look, they are fascinating to read!</li>
          </ul>
        </div>
      </div>
      <div class="col-6">
        <h2 id="outsideHeader" class="centerpad headercolor">Keep in touch with us! <span id="outsideArrow">&#x25BC;</span></h2>
        <div id="outsideContent" class="listmargin" style="display: none;">
          <ul class="outerlist">
            <li>Check out our <a class="linktext" href="https://www.facebook.com/FrederictonRegionMuseum" target="_blank">FACEBOOK</a> page for updates</li>
            <li>Support our work with a charitable donation to <a class="linktext" href="https://www.canadahelps.org/en/charities/fredericton-region-museum/?mprompt=1" target="_blank">CANADA HELPS</a></li>
            <li><a class="linktext" href="./contact-us.php" target="_blank">CONTACT US</a> for further information about the Fredericton Region Museum's Digital Volunteers</li>
          </ul>
        </div>
      </div>
    </div>
  </main>

  <?php include("footer.php"); ?>
</body>

</html>
