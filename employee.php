<?php
include("connect.php");
include("CLASSES/DocumentType.php");
include("CLASSES/Document.php");
include("CLASSES/Collection.php");
include("CLASSES/Employee.php");
include("CLASSES/Volunteer.php");

session_start();
//start of Role check Code XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
?>
<style>
  #btnRole1,
  #btnRole2,
  #btnRole3 {
    display: none;
  }
</style>
<?php


if (!isset($_SESSION['employee'])) {
  header('location:index.php'); //mabe also open the employee login 
  
} else {
  //echo 'empty employee session (KEEP UNTIL NOT HARD CODED ID)<BR>';
  //  $employee = $_SESSION['employee'];
  $empID = $_SESSION['employee'];

  //if empid not null then??
 
  $empRolesArray = Employee::GetEmpRoles($empID); //is an array
  
  //check if admin:
  if (in_array(4,  array_column($empRolesArray, 0))) {
    //echo '4';
    $notFound = false;
?>
    <style>
      #btnRole3 {
        display: block;
      }
    </style>
  <?php
  }
  //check if Approver:
  if (in_array(14,  array_column($empRolesArray, 0))) {
    //echo "14";
    $notFound = false;
  ?>
    <style>
      #btnRole2 {
        display: block;
      }
    </style>
  <?php
  }
  //check if uploader:
  if (in_array(24,  array_column($empRolesArray, 0))) {
    //echo "24";
    $notFound = false;
  ?>
    <style>
      #btnRole1 {
        display: block;
      }
    </style>
  <?php
  }

  //check if the role was found in the array:
  if ($notFound) {
    echo 'Role Not Found in Array!';
  ?>
    <style>
      #btnRole1,
      #btnRole2,
      #btnRole3 {
        display: none;
      }
    </style>
<?php
  }

} //end if else (user login

//END of Role check Code XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

if (isset($_GET["msg"])) {
  //display a error message if one comes back
  echo "<script>alert('" . $_GET["msg"] . "')</script>";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!--linking the jquery lib, this needs to be above the bootstrap link!-->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>


  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <?php include("e_head.php"); ?>
  <link rel="stylesheet" href="./CSS/employee.css">
  <script src="./JS/openseadragon/openseadragon.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/1.3.8/FileSaver.min.js"></script>
  <script>
    //Tabs stuff
    function openTab(evt, tabName) {
      // Declare all variables
      var i, tabcontent, tablinks, uTabContent;

      // Get all elements with class="tabcontent" and hide them
      tabcontent = document.getElementsByClassName("tabcontent");
      for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
      }
      //hide the upload tab content too! (but not the tab links)
      uTabContent = document.getElementsByClassName("uTabContent");
      for (i = 0; i < uTabContent.length; i++) {
        uTabContent[i].style.display = "none";
      }

      aTabContent = document.getElementsByClassName("aTabContent");
      for (i = 0; i < aTabContent.length; i++) {
        aTabContent[i].style.display = "none";
      }
      // Get all elements with class="tablinks" and remove the class "active"
      tablinks = document.getElementsByClassName("tablinks");
      for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
      }

      // Show the current tab, and add an "active" class to the button that opened the tab
      document.getElementById(tabName).style.display = "block";
      evt.currentTarget.className += " active";
    } //end of openTab
    //upload tabs stuff
    function openUploadTab(evt, tabName) {
      var i, uTabContent, uTabLinks;
      //various elements we don't want shown YET:
      document.getElementById("pagesSorting").style.display = "none";
      document.getElementById("btnUpload").style.display = "none";
      document.getElementById("updateDocTypeTable").style.display = "none";
      document.getElementById("updateCollectionTable").style.display = "none";

      uTabContent = document.getElementsByClassName("uTabContent");
      for (i = 0; i < uTabContent.length; i++) {
        uTabContent[i].style.display = "none";
      }
      uTabLinks = document.getElementsByClassName("uTabLinks");
      for (i = 0; i < uTabLinks.length; i++) {
        uTabLinks[i].className = uTabLinks[i].className.replace(" active", "");
      }
      document.getElementById(tabName).style.display = "block";
      evt.currentTarget.className += " active";
    }
    //admin subtabs:
    function openAdminTab(evt, tabName) {
      var i, aTabContent, aTabLinks;
      aTabContent = document.getElementsByClassName("aTabContent");
      for (i = 0; i < aTabContent.length; i++) {
        aTabContent[i].style.display = "none";
      }
      aTabLinks = document.getElementsByClassName("aTabLinks");
      for (i = 0; i < aTabLinks.length; i++) {
        aTabLinks[i].className = aTabLinks[i].className.replace(" active", "");
      }
      document.getElementById(tabName).style.display = "block";
      evt.currentTarget.className += " active";
    }

    //generate tables with documents and employees
    function generateDocTable(evt, tabName){
      let aTabContent = document.getElementsByClassName("tableContent");
      for (i = 0; i < aTabContent.length; i++) {
        aTabContent[i].style.display = "none";
      }

      aTabLinks = document.getElementsByClassName("aTabLinks");
      for (i = 0; i < aTabLinks.length; i++) {
        aTabLinks[i].className = aTabLinks[i].className.replace(" active", "");
      }
      document.getElementById(tabName).style.display = "block";
      evt.currentTarget.className += " active";
    }

    

    function getDocumentData(fileId) {
      let targetData = "document" + fileId;
      let input = document.getElementById(targetData);
      let image = document.getElementById(fileId + "img");
      document.getElementById("document_label").innerHTML = input.getAttribute("data-name");
      document.getElementById("modal_document_image").innerHTML = "<img src='" + image.getAttribute("src") + "' class='w-100 d-flex' style='justify-content: center;'>";
      document.getElementById("document_description").innerHTML = input.getAttribute("data-desc");
      document.getElementById("document_page_count").innerHTML = "Total number of pages in this document: " + input.getAttribute("data-numpages");
      document.getElementById("document_type").innerHTML = "Type: " + input.getAttribute("data-type");
      document.getElementById("document_category").innerHTML = "Category: " + input.getAttribute("data-category");
      document.getElementById("hidden_id").setAttribute("value", fileId);
      if (input.getAttribute("data-single") == "true") {
        document.getElementById("modal_choose_doc").setAttribute("value", "Resume Approval");
      }
    }
    //alex
    //Function to make an Ajax request to a proc page and return a document obj
    async function GetDocumentById(documentId){
        try{
           let response = await fetch("getDocumentById.php", {
              method: 'POST',
              body: JSON.stringify({documentId: documentId})
           })
           if(response.ok){
            let data = await response.json()
            return data
           }
        }
        catch(err){
          console.log(err)
        }
    }
    //Reasign function
    async function GenerateReassignModal(mode, id, documentId, statusId){
      let selectedId, targetRole
      let modal = document.querySelector("#reassignModal")
      modal.showModal()
      let inpDocName = document.querySelector("#inpDocName")
      let documentById = await GetDocumentById(documentId)
      inpDocName.value = documentById
      let btnConfirm = document.querySelector("#btnConfirm")
      let empContent = "<?php echo Employee::GetUnassignedEmployeesFormatted() ?>"
      let selcEmployee = document.querySelector("#empSelect")
      selcEmployee.innerHTML += empContent
      let volContent = "<?php echo Volunteer::GetUnassignedVolunteersFormatted() ?>"
      let selcVolunteer = document.querySelector("#volSelect")
      selcVolunteer.innerHTML += volContent
      let btnReturn = document.querySelector('#btnReturn')

      let btnCancel = document.querySelector("#btnCancel")
      btnCancel.addEventListener('click', function cancelModal(){
        modal.close()
        btnConfirm.removeEventListener('click', confirmClick)
        btnReturn.removeEventListener('click', evtReturnDocument)
        selcEmployee.innerHTML = "<option value=''>Employee List</option>"
        selcVolunteer.innerHTML = "<option value=''>Volunteer List</option>"
      })

      //events listeners to the select elements
      selcEmployee.addEventListener("change", () => {
    if (selcEmployee.value) {
        selcVolunteer.value = "";
        targetRole = "emp"
       }
    });

    selcVolunteer.addEventListener("change", () => {
        if (selcVolunteer.value) {
        selcEmployee.value = "";
        targetRole = "volunteer"
       }
    });
    //event listener to the Return document button
    
    if(btnReturn){
      btnReturn.addEventListener('click', evtReturnDocument)
    }
    function evtReturnDocument(){
      ReturnDocument(documentId)
      btnCancel.click()
    }

    modal.addEventListener('cancel', (event)=> {
            event.preventDefault()
          })

      let confirmClick = () =>{
        if(selcEmployee.value || selcVolunteer.value){
        selectedId = selcEmployee.value || selcVolunteer.value;
      }
      if (selcEmployee.value || selcVolunteer.value) {
        selectedId = selcEmployee.value || selcVolunteer.value;
        ReassignEmployee(id, selectedId, documentId, mode, targetRole, statusId);
      } else {
        alert("Please select an employee or volunteer.");
      }
        }
        btnConfirm.addEventListener('click', confirmClick)

}
    
      
    async function ReassignEmployee(prevId, currentId, documentId ,mode, targetRole, statusId){
        let data = {
          prevId,
          currentId,
          documentId,
          mode,
          targetRole,
          statusId
        }
        try{
          let response = await fetch('reassignDocument_proc.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(data)
        })
        if(response.ok){
          let modalSuccess = document.querySelector("#modalSuccess")
          modalSuccess.showModal()
          document.querySelector("#btnSucOk").addEventListener("click", ()=>{
             modalSuccess.close()
             window.location.reload()
          })
        }
        else {
          alert("something went wrong")
        }

        }
        catch(err){
          console.log(err)
        }
        
    }

    //Alex
    //Ajax call to get the remaining time for each document in the admin table
    async function GetTimeRemaining(id, documentId){
      try{
        let response = await fetch('timeRemaining_proc.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ volunteerId: id })
    });
    if(!response.ok){
      document.querySelector("#timeRemain"+id).innerHTML = '-'
    }
    else {
      data = await response.json()
      document.querySelector("#timeRemain"+id).innerHTML = data.timeRemaining + " Days"
      let circleStatus = document.querySelector("#circleStatus"+id)
      if (data.timeRemaining <= 15) {
        if(data.timeRemaining <= 0){
            await ReturnDocument(documentId)

        }
        else if(data.timeRemaining <= 5){
          circleStatus.innerHTML += `<img src='./IMAGES/ICONS/red-circle.png'></img>`;

        }
        else {
          circleStatus.innerHTML += `<img src='./IMAGES/ICONS/yellow-circle.png'></img>`;
        }
      }
      
      
    } 

    }
    catch(err){
        console.log(err)
        window.location.reload(true)
      }
    }

    //Alex
    //Ajax function to return the document when it's overdue
    async function ReturnDocument(documentId){
      try{
          let response = await fetch('returnDocument_proc.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({ documentId: documentId})
          })
          if(response.ok){
            let data = await response.json();
            if(data.success){
              let row = document.querySelector(`#tableRow${documentId}`)
              row.remove()
              alert(`Document: ${documentId} has been returned to the pool`)
            }
          }
          else {
            alert("Returning Document attempt failed.")
          }
      }
      catch(err){
        console.log(err)
        alert("Returning Document attempt failed.")
      }
    }
    


    //Alex
    //Events listeners to see documents when admin clicks its name
    function docNamesEventListeners(docId){
      let td = document.querySelector("#tdDocId"+docId)
      td.addEventListener('click', ()=>{
        populateViewTranscription(docId)
      })

    }

    async function getFolderName(docId){
      try{
        let response = await fetch('getDocumentFolder.php', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json'
            },
          body: JSON.stringify({documentId: docId})
        })
        
     
       if(response.ok){
          let folderName = await response.json()
         
          return folderName
        }
      }
      catch(err){
        console.log(err)
      }
    }

    async function getDocName(docId){
      try{
        let response = await fetch('getDocumentById.php', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json'
            },
          body: JSON.stringify({documentId: docId})
        })
        
     
       if(response.ok){
          let docName = await response.json()
         
          return docName
        }
      }
      catch(err){
        console.log(err)
      }
    }
    
       async function populateViewTranscription(docId){
          let modal = document.querySelector("#modalViewTransc")
         
          let txtDesc = document.querySelector("#txtDesc")
          let txtNotes = document.querySelector("#txtNotes")
          let folderName = await getFolderName(docId)
          let docName = await getDocName(docId)
          modal.showModal()
          modal.addEventListener('cancel', (event)=> {
            event.preventDefault()
          })
          document.querySelector('#lblDocName').innerText = docName
          //add event listener to close the modal
          let btnClose = document.querySelector('#btnDocClose')
          btnClose.addEventListener('click', ()=>{
            document.querySelector('#openseadragon1').innerHTML = ''
            modal.close()
          })
          $.ajax({
    type: "POST",
    url: "transcription_proc.php",
    data: {
     
      createOSDCanva: folderName
    },
    // dataType: "dataType",
    success: (arrayOfImages) => {
      let formatedImagesArray = createImageArray(arrayOfImages);
      var viewer = createOSDViewer(formatedImagesArray);
      let transcription = joinTranscText(arrayOfImages, 'transc')
      txtDesc.innerHTML = transcription
      let notes = joinTranscText(arrayOfImages, 'notes')
      txtNotes.innerHTML = notes

     
    }
  })

      }
    
    
      //function to create formatted array of images
      function createImageArray(arrayOfImages) {
       
  let formatedImagesArray = [];
  let resParsed = JSON.parse(arrayOfImages);
  resParsed.images.forEach(path => {
    formatedImagesArray.push(
      {
        type: "image",
        url: path
      }
    )
  });
  return formatedImagesArray;
}

//function to create viewer
function createOSDViewer(images) {
  //create the openseadragon viewer
  var viewer = OpenSeadragon({
    id: 'openseadragon1',
    prefixUrl: './JS/openseadragon/images/',
    showNavigator: true,
    navigatorPosition: 'TOP_RIGHT',
    showSequenceControl: true,
    nextButton: 'btnNext',
    previousButton: 'btnPrev',
    tileSources: images,
    sequenceMode: true,
    showReferenceStrip: true,
    maxZoomPixelRatio: 5,
    minZoomLevel: 0,
    defaultZoomLevel: 0,
    // debugMode: true
  });
  return viewer;
}


function joinTranscText(imagesObj, mode) {
 let obj = JSON.parse(imagesObj) //it takes the string formart and converts it to JS obj
    if(mode === 'transc'){
      obj.transcText[0] = obj.transcText[0].replace(/^\n/, '');
    return obj.transcText.reduce((acc, text) => acc + text, '');
    }
    else if (mode === 'notes'){
      obj.notesText[0] = obj.notesText[0].replace(/^\n/, '');
    return obj.notesText.reduce((acc, text) => acc + text)}
    
}


//Alex Function to visualize work done by volunteer

async function fetchHistory(volunteerId) {
  try {
    let response = await fetch('getHistory_proc.php', {
      method: 'POST',
      headers: { 'content-type': 'application/json' },
      body: JSON.stringify({ volunteerId: volunteerId })
    });
    if (response.ok) {
      return await response.json();
    }
  } catch (err) {
    console.log(err);
  }
}

async function ViewWorkDoneByVolunteer(volunteerId){
  let modal = document.querySelector("#modalWorkDone")
  modal.showModal()
  let workTable = await fetchHistory(volunteerId)
 

  modal.innerHTML = '<button id="btnWorkDoneClose" type="button" class="btn btn-dark">Close</button>'

  modal.innerHTML += workTable
  const buttons = document.querySelectorAll('.btn-view');

// Function to call when button is clicked
function callViewTranscribe() {
    populateViewTranscription(this.id.substring(7));
}

// Add event listener to each button
buttons.forEach(button => {
    button.addEventListener('click', callViewTranscribe);
});

// Add event listener to btnClose
let btnClose = document.querySelector("#btnWorkDoneClose");
btnClose.addEventListener('click', () => {
    // Remove event listener from each button
    buttons.forEach(button => {
        button.removeEventListener('click', callViewTranscribe);
    });
    modal.close();
    modal.innerHTML = '';
});

}
  </script>

</head>

<body>


  <?php include("empHeader.php"); ?>

  <!-- ################################################## MAIN CONTENT ################################################## -->
  <main>
    <!-- Tab links -->
    <div class="tab">
      <button id="btnRole1" class="tablinks" onclick="openTab(event, 'Upload')">Upload</button>
      <button id="btnRole2" class="tablinks" onclick="openTab(event, 'Approve')">Approve</button>
      <button id="btnRole3" class="tablinks" onclick="openTab(event, 'Admin')">Admin</button>
    

    </div>

    <!-- Tab content -->
    <div id="Upload" class="tabcontent">
      <div class="tab">
        <button class="uTabLinks" onclick="openUploadTab(event, 'newDoc')">Upload Scanned Document</button>
        <button class="uTabLinks" onclick="openUploadTab(event, 'uploadTranscription')">Upload Transcription</button>
        <button class="uTabLinks" onclick="openUploadTab(event, 'newDocType')">Add Document Type</button>
        <button class="uTabLinks" onclick="openUploadTab(event, 'updateDocType')">Update Document Type</button>
        <button class="uTabLinks" onclick="openUploadTab(event, 'addCollection')">Create Collection</button>
        <button class="uTabLinks" onclick="openUploadTab(event, 'updateCollection')">Update Collection</button>
        <button class="uTabLinks" onclick="openUploadTab(event, 'removeDoc')">Remove Document</button>
      </div>


      <!--NEW DOCUMENT FUNCTIONALITY-->
      <div id="newDoc" class="uTabContent">
        <form id="upload_doc_form" action="uploadDoc_proc.php" method="post" enctype="multipart/form-data">
          <table>
            <tr>
              <td>Document Name: </td>
              <td><input type="text" id="docName" name="docName" required="required"></td>
            </tr>
            <tr>
              <td>Document Type: </td>
              <td><select id="docType" name="docType" required="required">
                  <option value="">Select Document Type</option>
                  <?php DocumentType::GetTypesDropDown(); ?>
                </select></td>
            </tr>
            <tr>
              <td>Collection: </td>
              <td><select id="collection" name="collection" required="required">
                  <option value="">Select Collection</option>
                  <?php print(Collection::GetCollectionsDropDown()); ?>
                </select></td>
            </tr>
            <tr>
              <td>Description: </td>
              <td><textarea id="docDesc" name="docDesc" rows="4" cols="50" required="required" maxlength="500"></textarea></td>
            </tr>
          </table>
          <input type="hidden" name="numPages" id="numPages" value="" />
          <HR>
          <div>
            Please upload the images of the document pages all at once(.jpg, .jpeg, .png). <BR>
            Select the pages:
            <input type="file" accept="image/*" id="document" name="document[]" required="required" multiple="true" onchange="validateFileType()"><br>
            <!-- accepting images only based on meeting with client Feb 8th 2023-->
          </div>
          <div id="pagesSorting">
            Please sort the pages in the proper order then click on Confirm Order.
            <ul id='sortable'>
            </ul>
            <input type="button" name="btnConfirmPagesOrder" id="btnConfirmPagesOrder" value="Confirm Order" onclick="confirmPagesOrder()">
            <input type="hidden" name="pageOrder" id="pageOrder" value="" />
          </div>

          <input type="submit" name="btnUpload" id="btnUpload" value="Create Document">
        </form>
      </div>



      <script>
        function validateTextFile() {
          //we are only getting 0 or 1 files
          var inputElement = document.getElementById('textfile');
          var files = inputElement.files;
          if (files.length == 0) {
            alert("Transcription text file not found. Please select one.");
            return false;
          } else {
            var filename = files[0].name;

            /* getting file extension eg- .jpg,.png, etc */
            var extension = filename.substr(filename.lastIndexOf("."));

            /* define allowed file types */
            var allowedExtensionsRegx = /(\.doc|\.docx|\.pdf|\.txt)$/i;

            /* testing extension with regular expression */
            var isAllowed = allowedExtensionsRegx.test(extension);

            if (!isAllowed) {
              alert("Invalid file type for transcription. Please try again.");
              document.getElementById("btnUpload").style.display = "none";
              return false;
            }
          } //end else
        } //end ValidTextFile

        function validateFileType() {
          document.getElementById("btnUpload").style.display = "none";
          document.getElementById("sortable").innerHTML = "";
          var inputElement = document.getElementById('document');
          var files = inputElement.files;
          var numPages = files.length;
          if (numPages == 0) {
            alert("Please select pages first.");
            return false;
          } else {
            /*Save the number of pages in storage*/
            document.getElementById("numPages").value = numPages;
            /* iterating over the files array */
            for (var i = 0; i < files.length; i++) {
              var filename = files[i].name;

              /* getting file extension eg- .jpg,.png, etc */
              var extension = filename.substr(filename.lastIndexOf("."));

              /* define allowed file types */
              var allowedExtensionsRegx = /(\.jpg|\.jpeg|\.png)$/i;

              /* testing extension with regular expression */
              var isAllowed = allowedExtensionsRegx.test(extension);

              if (!isAllowed) {
                alert("Invalid File Type.");
                document.getElementById("btnUpload").style.display = "none";
                return false;
              }
            } //end for loop
          } //end else


          //alert("File type is valid for the upload");
          /* file upload logic goes here... */
          if (numPages == 1) {
            document.getElementById("btnUpload").style.display = "block";
          } else {
            document.getElementById("pagesSorting").style.display = "block";
            /* iterating over the files array */
            for (var i = 0; i < files.length; i++) {
              var filename = files[i].name;
              document.getElementById("sortable").innerHTML += "<li class='ui-state-default' id='" + filename + "'><span class='ui-icon ui-icon-arrowthick-2-n-s'></span>" + filename + "</li>";
              //THIS WORKS!!
            } //end for loop
          } //end else
        } //end ValidateFileType


        //pages sorting:
        $(function() {
          $("#sortable").sortable({
            update: function(event, ui) {
              var benefits = $(this).sortable('toArray').toString();
              //benefits has the order of the pages! (it says the ID!!)
              //alert(benefits + " benefits");
              // sessionStorage.setItem("pageOrder", benefits);
            }
          });
          $("#sortable").disableSelection();
        });

        function confirmPagesOrder() {
          document.getElementById("btnUpload").style.display = "block";
        }
      </script>



      <!--UPLOAD TRANSCRIPTION -->
      <div id="uploadTranscription" class="uTabContent">
        Do you want to add transcription text to a document? <BR>
        <form id="transcriptionForm" action="uploadTranscription_proc.php" method="post">
          Please select the collection:
          <select id="collectionTranscribed" name="collectionTranscribed" required="required" onchange="ShowDocumentsRdo(this.value)">
            <!-- this is to put the "required"to work, and because without it, when the page first load, the first collection 
                  was selected but checkbox images were displayed. This forces the user to select something and update the checkboxes -->
            <option value="">Select Collection</option>
            <?php print(Collection::GetCollectionsDropDown()); ?>
          </select>
          <table id="documentsByCollectionRDO" name='documentsByCollectionRDO'>

          </table>
          <div class="d-none" id="addText" name="addText">

            <table id="docTable">
              <tr>
                <td></td>
                <td><input type="submit" id="addTranscription" name="addTranscription" value="Add Transcription"></td>
              </tr>
            </table>

            <!--we have the doc id in the JS function below (AddText)-->
          </div>
        </form>
      </div>

      <script>
        function ShowDocumentsRdo(collId) {
          $('tr[id*="docRow"]').remove();
          if (collId.length == 0) {
            document.getElementById("documentsByCollectionRDO").innerHTML = "";
            return;
          } else {
            const xmlhttp = new XMLHttpRequest();
            xmlhttp.onload = function() {
              document.getElementById("documentsByCollectionRDO").innerHTML = this.responseText;

            }
            xmlhttp.open("GET", "uploadTranscription_AJAX.php?q=" + collId);
            xmlhttp.send();
          }
        } //end ShowDocuments

        function AddText(docId) {
          // document.getElementById("addText").style.display = "block"; //show the addText div
          //$docId is the id of the document selected (radio buttons)
          $("#saveMessage").remove();
          $.ajax({
            type: "POST",
            url: "uploadTranscription_AJAX.php",
            data: {
              getDocumentDetails: docId,
            },
            success: function(response) {
              response = JSON.parse(response);
              $("#addText").removeClass('d-none').show();
              $('tr[id*="docRow"]').remove();

              $.each(response, function(index, value) {
                var row = $('<tr id="docRow' + index + '">');
                row.append(value.image);
                row.append(value.textarea);
                row.append('</tr>');
                $('#docTable').prepend(row);
              });

            }
          });
        } //end AddText


        $(function submitTranscribedText() {
          $("#transcriptionForm").submit(function(e) {
            e.preventDefault();

            $(".transcriptionText").each(function() {
              let imageName = $(this).attr('name');
              let transcribedText = $(this).val();
              let docId = $(this).attr('id');

              $.ajax({
                type: "POST",
                url: "uploadTranscription_AJAX.php",
                data: {
                  txtFileName: imageName,
                  transcribedText: transcribedText,
                  docId: docId
                },
                success: function(response) {
                  if (response) {

                    $("#saveMessage").remove();
                    $("#addTranscription").after("&nbsp <span id=saveMessage>Document successfully saved.</span>");

                  }

                }
              });
            })
          })
        }) //end submitTranscribedText
      </script>



      <!--ADD DOCUMENT TYPE-->
      <div id="newDocType" class="uTabContent">
        Need a new document type?
        <form action="addDocType.php" method="post">
          <table>
            <tr>
              <td>Category:</td>
              <td><input type="text" id="docCategory" name="docCategory" required="required" maxlength="60"></td>
            </tr>
            <tr>
              <td>Description <i>(optional)</i>: </td>
              <td><input type="text" id="docTypeDesc" name="docTypeDesc" maxlength></td>
            </tr>
            <tr>
              <td><input type="submit" name="addType" value="Add Type"></td>
            </tr>
          </table>
        </form>
        <hr>
        Existing document types:
        <ul>
          <?php DocumentType::GetTypesDisplay() ?>
        </ul>
      </div>


      <!--UPUDATE DOCUMENT TYPE-->
      <div id="updateDocType" class="uTabContent">
        Need to update a document type?
        <form action="updateDocType.php" method="post">
          <table>
            <tr>
              <td>Select the document type: </td>
              <td><select id="docTypeUpdate" name="docTypeUpdate" required="required" onchange="ShowDocTypeTable()">
                  <option value=""> </option>
                  <?php DocumentType::GetTypesDropDown(); ?>
                </select></td>
            </tr>
          </table>
          <table id="updateDocTypeTable" name="updateDocTypeTable">
            <tr>
              <td> Category: </td>
              <td><input type="text" id="docCategoryUpdate" name="docCategoryUpdate" required="required" maxlength="60"></td>
            </tr>
            <tr>
              <td>Description <i>(optional)</i>: </td>
              <td><input type="text" id="docTypeDescUpdate" name="docTypeDescUpdate" maxlength></td>
            </tr>
            <tr>
              <td><input type="submit" name="updateType" value="Update Type"></td>
            </tr>
          </table>
        </form>
        <hr>
      </div>



      <script>
        function ShowDocTypeTable() {
          //show the rest of the form:
          document.getElementById("updateDocTypeTable").style.display = "block";
        }
      </script>


      <!--ADD COLLECTION-->
      <div id="addCollection" class="uTabContent">
        <form action="create_collection.php" method="post">
          <table>
            <tr>
              <td>Collection Name: </td>
              <td><input type="text" id="collectionName" name="collectionName"></td>
            </tr>
            <tr>
              <td>Time Period: </td>
              <td><input type="text" id="timePeriod" name="timePeriod"></td>
            </tr>
            <tr>
              <td>Description: </td>
              <td><textarea id="collDesc" name="collDesc" rows="4" cols="50" maxlength="500"></textarea></td>
            </tr>

            <td><input type="submit" name="createCollection" value="Create Collection"></td>
            </tr>
          </table>

        </form>
        <hr>
        Existing collections:
        <ul>
          <?php Collection::GetCollectionsDisplay() ?>
        </ul>
      </div>


      <!--UPDATE COLLECTION-->
      <div id="updateCollection" class="uTabContent">
        Need to update a collection?
        <form action="updateCollection.php" method="post">
          <table>
            <tr>
              <td>Select the collection: </td>
              <td><select id="collectionUpdate" name="collectionUpdate" required="required" onchange="ShowCollectionTable()">
                  <option value="">Select Collection</option>
                  <?php print(Collection::GetCollectionsDropDown()); ?>
                </select></td>
            </tr>
          </table>
          <table id="updateCollectionTable" name="updateCollectionTable">
            <tr>
              <td> Collection Name: </td>
              <td><input type="text" id="collNameUpdate" name="collNameUpdate" required="required" maxlength="45"></td>
            </tr>
            <tr>
              <td>Time Period: </td>
              <td><input type="text" id="timePeriodUpdate" name="timePeriodUpdate" maxlength="45"></td>
            </tr>
            <tr>
              <td>Description: </td>
              <td><input type="text" id="collDescUpdate" name="collDescUpdate" maxlength="500"></td>
            </tr>
            <tr>
              <td><input type="submit" name="updateCollection" value="Update Collection"></td>
            </tr>
          </table>
        </form>
        <hr>
      </div>

      <script>
        function ShowCollectionTable() {
          //show the rest of the form:
          document.getElementById("updateCollectionTable").style.display = "block";
        } //end ShowCollectionTable
      </script>


      <!--REMOVE DOCUMENT-->
      <div id="removeDoc" class="uTabContent">
        Need to remove a document? <BR>
        <form action='removeDoc_proc.php' method='POST'>
          Please select the collection:
          <select id="collectionRemove" name="collectionRemove" required="required" onchange="ShowDocuments(this.value)">
            <!-- this is to put the "required"to work, and because without it, when the page first load, the first collection 
            was selected but checkbox images were displayed. This forces the user to select something and update the checkboxes -->
            <option value="">Select Collection</option>
            <?php print(Collection::GetCollectionsDropDown()); ?>
          </select>

          <table id="documentsByCollectionCHK" name='documentsByCollectionCHK'>

          </table>
          <input type="submit" name="removeDoc" value="Remove Document(s)">
        </form>
      </div>

      <script>
        function ShowDocuments(collId) {
          if (collId.length == 0) {
            document.getElementById("documentsByCollectionCHK").innerHTML = "";
            return;
          } else {
            const xmlhttp = new XMLHttpRequest();
            xmlhttp.onload = function() {
              document.getElementById("documentsByCollectionCHK").innerHTML = this.responseText;
            }
            xmlhttp.open("GET", "removeDoc_AJAX.php?q=" + collId);
            xmlhttp.send();
          }
        }
      </script>


    </div>

   

    <!-- Approve Section -->
    <div id="Approve" class="tabcontent">
      <?php
      $activeApproverDocument = Employee::CheckActiveApproverDocument($empID);
      if ($activeApproverDocument == 0) {
        print(Document::GetFormattedApproverDocuments());
      } else {
        print(Document::GetFormattedActiveApproverDocument($activeApproverDocument));
      }
      include("modal_approver.php");
      ?>
    </div>

   <!-- Admin Section -->
<div id="Admin" class="tabcontent">
  <div class="tab">
    <button class="aTabLinks" onclick="openAdminTab(event, 'newStaff')">Add New Staff</button>
    <button class="aTabLinks" onclick="openAdminTab(event, 'removeUser')">Remove User</button>
    <button class="aTabLinks" onclick="openAdminTab(event, 'removeStaff')">Remove Staff</button>
    <button class="aTabLinks" onclick="openAdminTab(event, 'tasks')">Tasks</button>

  </div>
   <!-- View current Transcription Dialog-->
   <dialog id="modalWorkDone" class="text-center">
    

    <div>
   </dialog>
  <!-- View current Transcription Dialog-->
  <dialog id="modalViewTransc">
    <h1 id="lblDocName" class="text-center"></h1>
    <hr/>
    <div class="d-flex justify-content-around align-items-center mx-2">
      <div id="openseadragon1" style="width: 800px; height: 600px;"></div>
      <div class="d-flex flex-column my-2 mx-2 h-100 w-100">
        <textarea readonly rows="15" class="my-2" id='txtDesc'></textarea>
        <textarea readonly rows="5" class="my-2" id='txtNotes'></textarea>
        <button id="btnDocClose" class="btn btn-dark">Close</button>
      </div>
    </div>
      
  </dialog>
  <!-- Tasks-->
  <div id="tasks" class="aTabContent">
      <!-- <button class="aTabLinks" onclick="generateDocTable(event, 'empTable')">Employees</button> -->
      <button class="aTabLinks" onclick="generateDocTable(event, 'volTable')">Volunteers</button>

      <dialog id="reassignModal" class="h-50">
      <div class="d-flex flex-column justify-content-around align-items-center p-2  h-100 w-100">
          <h3>Please Select an Employee or a Volunteer to be assigned</h3>
          <input class="w-100 p-1 text-center" id="inpDocName" type="text" readOnly disabled></input>
          <div class="mx-2 w-75">
            <select id="empSelect" class="w-100 p-2 my-1">
              <option value="">Employeer List</option>
            
                
            </select>

            <select id="volSelect" class="w-100 p-2 my-1">
                <option value="">Volunteer List</option>
            </select>
          </div>
          <div class="mx-2">
            <button id="btnConfirm" type='button' class='btn btn-dark'>Confirm</button>
            <button id="btnCancel" type='button' class='btn btn-dark'>Cancel</button>
            <button id="btnReturn" type='button' class='btn btn-danger'>Return Document</button>
          </div>
      </div>
      </dialog>
  </div>

  <!-- Tasks Table-->
  <!-- <div id="empTable" class="tableContent aTabContent">
      <h1>Employees</h1>
      <?php
       //echo Document::GetAllAvailableDocumentsEmployeesAsHtmlTable()

      ?>
  </div> -->
  
  <div id="volTable" class="tableContent aTabContent">
      <h1>Volunteers</h1>
      <?php
        echo Document::GetAllAvailableDocumentsVolunteersAsHtmlTable()
      ?>
  </div>

  <!-- Success Modal-->
  <dialog id="modalSuccess">
      <div class="d-flex p-2 flex-column justify-content-around">
            <p class="h3">Success<p>
        
            <hr>
                <p class="mx-auto h2">The Document has been reassigned!</p>
            <hr>
            <button id="btnSucOk" type="button" class="btn btn-dark" >OK</button>
      </div>
  </dialog>

  <script>
    
  </script>
  <!-- NEW STAFF-->
  <div id="newStaff" class="aTabContent">
    <div>
    <form method="post" action="add_staff_proc.php">
            <table>
              <tr>
                <th colspan="2">Add new employee:</th>
              </tr>
              <tr>
                <td>Username:</td>
                <td><input type="text" id="txtUsername" name="txtUsername"></td>
              </tr>
              <tr>
                <td>Password:</td>
                <td id="new_emp_passwd">
                  <input type="password" id="txtPassword" name="txtPassword">
                  <span class="input-group-addon" id="viewPass">
                    <i class="bi bi-eye" id="show_staff_passwd" style="display:none"></i>
                    <i class="bi bi-eye-slash" id="hide_staff_passwd"></i>
                  </span>
                </td>
              </tr>
              <tr rowspan="3">
                <td>Role:</td>
                <td>&nbsp;<input type="checkbox" value="Approver" id="chkApprover" name="chkApprover"> Approver<BR>
                  &nbsp;<input type="checkbox" value="Uploader" id="chkUploader" name="chkUploader"> Uploader<BR>
                  &nbsp;<input type="checkbox" value="Admin" id="chkAdmin" name="chkAdmin"> Admin</td>
              </tr>
              <tr>
                <td colspan="2"><input type="submit" value="Create User" id="btnAddUser" name="btnAddUser"></td>
              </tr>
            </table>
          </form>
    </div>
    <span id="spnAddError">&nbsp;</span>
    <?php
    if (isset($_GET["addStaffMessage"])) {
      echo "<p>" . $_GET["addStaffMessage"] . "</p>";
    }
    ?>
  </div>
   
  <!-- REMOVE USER -->
  <div id="removeUser" class="aTabContent">
    <div>
      <form method="post" action="remove_user_proc.php">
        <table>
          <tr>
            <th colspan="2">Remove User:</th>
          </tr>
          <tr>
                <td>Select User:</td>
                <td>
                <select list="dtlUser" id="userList" name="userList">
                    <option value="Select a User">Select a User</option>
                    <?php
                      echo Volunteer::GetAllVolunteersFormatted();
                    ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td colspan="2"><input type="submit" value="Delete User" id="btnRemoveUser" name="btnRemoveUser"></td>
              </tr>
        </table>
      </form>
    </div>
    <span id="spnDelUserError">&nbsp;</span>
    <?php
    if (isset($_GET["removeUserMessage"])) {
      echo "<p>" . $_GET["removeUserMessage"] . "</p>";
    }
    ?>
  </div>

  
  
  <!-- REMOVE STAFF -->
  <div id="removeStaff" class="aTabContent">
  <div>
          <form method="post" action="remove_staff_proc.php">
            <table>
              <tr>
                <th colspan="2">Remove Staff:</th>
              </tr>
              <tr>
                <td>Select Staff:</td>
                <td>
                  <select id="staffList" name="staffList">
                    <option value="Select a Staff Member">Select a Staff Member</option>
                    <?php
                       echo Employee::GetAllEmployeesFormatted();
                    ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td colspan="2"><input type="submit" value="Delete Staff" id="btnRemoveStaff" name="btnRemoveStaff"></td>
              </tr>
            </table>
          </form>
        </div>
        <span id="spnDelStaffError">&nbsp;</span>
        <?php
        if (isset($_GET["removeStaffMessage"])) {
          echo "<p>" . $_GET["removeStaffMessage"] . "</p>";
        }
        ?>
  </div>
</div>



  </main>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  <script type="text/javascript" src="./JS/utils.js"></script>
  <script src="https://cdn.rawgit.com/mgalante/jquery.redirect/master/jquery.redirect.js"></script>
  <script type="text/javascript" src="JS/elogout.js"></script>
  <script type="text/javascript" src="JS/employee.js"></script>
</body>

</html>
