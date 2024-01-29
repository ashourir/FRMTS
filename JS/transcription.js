$(document).ready(() => {
  createOSDCanva(folderName);
  downloadAsPdf();
  goToVolunteerDashboard();
  goToEmployeeDashboard();
  goToViewTranscribedDocuments();
  hideEmployeeMenu();
  showCompleteMessageModalCall();
  showDropDocumentMessageModalCall();
  showRejectDocumentMessageModalCall();
  saveButton();
});
let currentImage = "";
let filePath = "";
let logoPath = "./IMAGES/logo.jpg";
let pdfArray = [];
let intervalIdMessage;


//------------------------------------------ createOSDCanva SECTION -----------------------------------------------
/**
 * @summary This function is responsible for creating the Open Sea Dragon canva viewer.
 * It uses the folder name path comming from the PHP Document Object on transcription.php
 * @argument {*} folderName
 */
function createOSDCanva(folderName) {
  $.ajax({
    type: "POST",
    url: "transcription_proc.php",
    //I can send login information to decide what function to execute in the proc.php
    data: {
      //folderName is coming from transcription.php after the footer php include
      //This folder name is coming from the php Document object
      createOSDCanva: folderName,
    },
    // dataType: "dataType",
    success: (arrayOfImages) => {
      pdfArray = JSON.parse(arrayOfImages);
      let formatedImagesArray = createImageArray(arrayOfImages);
      var viewer = createOSDViewer(formatedImagesArray);
      attachEventHandlers(viewer);
    }
  })
}

/**
 * @summary Receives an JSON obj comming as response from ajax call, parses it and creates an array of images
 * @param {Array} arrayOfImages 
 * @returns a formated Array of images to feed the openSeaDragon obj
 */

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

/**
 * @summary Creates the OpenSeaDragon Obj viewer based on an array of images
 * @param {Array} images 
 */
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

/**
 * @summary This function is used to attach event handlers to the OSD viewer. That way, no no function is loaded if there's no image in the viewer
 * @param {*} viewer 
 */
function attachEventHandlers(viewer) {

  //This event handler will be trigered when the openSeaDragon image finishes loading
  viewer.addHandler("open", function () {
    currentImage = viewer.world.getItemAt(0); // returns an object with many information about the current image
    filePath = currentImage.source.url; //    returns this format   --->           ./UPLOADS/test/gog.jpg
    getTxtDataByFilePath(filePath);
    getTxtNotesDataByFilePath(filePath);
    autoSaveMessage(120);
    createPDFCanvaFull(pdfArray);
  })

}

/**
 * 
 * @summary This function will take a file path and call and ajax function passing the 
 * file path to retrieve the text file content and display in the transcription textarea
 * @param {*} filePath 
 */
function getTxtDataByFilePath(filePath) {
  $.ajax({
    type: "POST",
    url: "./transcription_proc.php",
    data: { getTxtDataByFilePath: filePath },
    success: function (res) {
      $("#txtTranscription").val(res);
    }
  });
}

/**
 * 
 * @summary This function will take a file path and call and ajax function passing the 
 * file path to retrieve the text file content and display in the Notes textarea
 * @param {*} fileName 
 */
function getTxtNotesDataByFilePath(fileName) {
  $.ajax({
    type: "POST",
    url: "transcription_proc.php",
    data: { getTxtNotesDataByFilePath: fileName },
    success: function (res) {
      $("#txtNotes").val(res);

    }

  });
}

function saveButton() {
  if ($('#btnSave').length) {
    $("#btnSave").on('click', () => {
      storeInTextFileAJAX(filePath);
      alert("Document's progress saved");
    })

  }
}

/**
 * @summary This function attachs the save functionality to the save button. It also attachs a auto-saving functionality on every 2 minutes if the save button is available in the page.
 */
function autoSaveMessage(count) {

  if (intervalIdMessage) {
    clearInterval(intervalIdMessage);
  }

  let countdown = count;
  intervalIdMessage = setInterval(() => {
    $("#spanSaveMsg").css('background-color', '#d3d3d3');
    $("#spanSaveMsg").css('color', 'black');
    $("#spanSaveMsg").css('width', '100%');
    $("#spanSaveMsg").text('Next document auto save in ' + countdown + ' seconds.');

    if (countdown <= 5) {
      $("#spanSaveMsg").css('background-color', '#6696ba');
      $("#spanSaveMsg").css('color', 'black');
      $("#spanSaveMsg").css('width', '100%');

    }

    if (countdown <= 2) {
      $("#spanSaveMsg").css('background-color', '#b8eda1');
      $("#spanSaveMsg").css('color', 'black');
      $("#spanSaveMsg").css('width', '100%');
    }

    if (countdown == 0) {
      storeInTextFileAJAX(filePath);
      $("#spanSaveMsg").text('Document Auto Saved!');
      countdown = count;
    }
    countdown--;

  }, 1000);

}

/**
 * @summary This function will take both text area content and throw it to an ajax call to be processed and stored in a text file
 * @param {*} fileName 
 */
function storeInTextFileAJAX(fileName) {
  let txtTransc = $('#txtTranscription').val();
  let txtNotes = $('#txtNotes').val();
  $.ajax({
    type: "POST",
    url: "transcription_proc.php",
    data: {
      txtTransc: txtTransc,
      txtNotes: txtNotes,
      fileName: fileName
    },
    success: function (data) {
    }
  });

}

/**
 * @summary Will attach an event handler to call the generatePDF() function
 */
function downloadAsPdf() {
  $("#print_pdf").on('click', () => {
    // Default export is a4 paper, portrait, using millimeters for units
    generatePDF(pdfArray);
  });
}

/**
 * @summary Attach an event handler to send the user to volunteer.php
 */
function goToVolunteerDashboard() {
  $("#btnVolunteerHome").on('click', () => {
    window.location.replace('volunteer.php?coll=all&page=1&count=25');
  })

}
/**
 * @summary Attach an event handler to send the user to employee.php
 */
function goToEmployeeDashboard() {
  $("#btnEmployeeHome").on('click', (e) => {
    window.location.replace('employee.php');

  })


}
/**
 * @summary Attach an event handler to send the user to view_transcribed_documents.php
 */
function goToViewTranscribedDocuments() {
  $("#btnViewTranscDocHome").on('click', () => {
    window.location.replace('view-transcribed-documents.php?coll=all&page=1&count=25');
  })

}

/**
 * @summary just to hide the header and navbar
 */
function hideEmployeeMenu() {
  $("header").hide();
  $("nav").hide();
}

/**
 * @summary This function attach events to the "Complete" button for both volunteers, transcriber and proofreader
 */
function showCompleteMessageModalCall() {


  //calls the modal when clicked on the button for transcriber
  $("#btnCompleteTranscribe").on('click', () => {
    $('.confirmComplete').modal('show');
  })

  //calls the modal when clicked on the button for proofreader 
  $("#btnCompleteProofread").on('click', () => {
    $('.confirmProofReadComplete').modal('show');
  })

  //calls the modal when clicked on the button for approver 
  $("#btnCompleteApprove").on('click', () => {
    $('.confirmApproveComplete').modal('show');
  })

  //Calls the UpdateDocument if transcriber volunteer clicks on the modal confirmation button
  $('#btnCompleteConfirm').click(function () {
    UpdateDocument();
    $('.confirmComplete').modal('hide');
    // add code to call ajax to change the document status on database and redirect user to volunteer.php
  });

  //Calls the UpdateDocument if proofread volunteer clicks on the modal confirmation button
  $('#btnCompleteProofReadConfirm').click(function () {
    UpdateDocument();
    $('.confirmComplete').modal('hide');
  });

  //Calls the UpdateDocument if proofread volunteer clicks on the modal confirmation button
  $('#btnCompleteApproveConfirm').click(function () {
    ApproveDocument();
    $('.confirmComplete').modal('hide');
  });

  //Hides the confirmation modal when click on the cancel button
  $('#btnCompleteCancel').click(function () {
    $('.confirmComplete').modal('hide');
  });


}

function clearTxtFileContents() {
  $.ajax({
    type: "POST",
    url: "approver_transcription_proc.php",
    data: {
      deleteTxtFiles: folderName
    },
  });


}

/**
 * @summary This function attach events to the "Reject" button for approver.
 */
function showRejectDocumentMessageModalCall() {

  //---------- employee/approver -----------
  $("#employeeBtnReject").on('click', () => {
    $('.employeeConfirmReject').modal('show');
  })
  $('#employeeBtnRejectConfirm').click(function () {

    clearTxtFileContents(rejectDocumentApproval());

    storeInTextFileAJAX(filePath,);

    $('.employeeConfirmReject').modal('hide');
  });
  $('#employeeBtnRejectCancel').click(function () {
    $('.employeeConfirmReject').modal('hide');
  });
}

/**
 * @summary This function attach events to the "Drop" button for both volunteers: transcriber and proofreader. And for approver as well.
 */
function showDropDocumentMessageModalCall() {

  //---------- employee/approver -----------
  $("#employeeBtnDrop").on('click', () => {
    $('.employeeConfirmDrop').modal('show');
  })
  $('#employeeBtnDropConfirm').click(function () {
    employeeDropDocument();
    $('.employeeConfirmDrop').modal('hide');
  });
  $('#employeeBtnDropCancel').click(function () {
    $('.employeeConfirmDrop').modal('hide');
  });

  //----------- volunteer ------------
  $("#btnDrop").on('click', () => {
    $('.confirmDrop').modal('show');
  })
  $('#btnDropConfirm').click(function () {
    dropDocument();
    $('.confirmDrop').modal('hide');
  });
  $('#btnDropCancel').click(function () {
    $('.confirmDrop').modal('hide');
  });
}


function dropDocument() {
  $.ajax({
    type: "POST",
    url: "transcription_proc.php",
    data: {
      updateDocument: "quit",
    },
    success: function (response) {
      if (response === "success") {
        window.location.replace('volunteer.php?coll=all&page=1&count=25');
      }

    }
  });

}

function rejectDocumentApproval() {
  $.ajax({
    type: "POST",
    url: "approver_transcription_proc.php",
    data: {
      rejectDocument: docId,
    },
    success: function (response) {
      (response == 1) ? $.redirect("employee.php") : console.log("Error rejecting document");

    }
  });

}

function ApproveDocument() {
  $.ajax({
    type: "POST",
    url: "approver_transcription_proc.php",
    data: {
      approveDocument: docId,
    },
    success: function (response) {
      (response == 1) ? $.redirect("employee.php") : console.log("Error marking document as complete");

    }
  });

}

function UpdateDocument() {
  $.ajax({
    type: "POST",
    url: "transcription_proc.php",
    data: {
      updateDocument: "complete",
    },
    success: function (response) {
      if (response === "success") {
        window.location.replace('volunteer.php?coll=all&page=1&count=25');
      }
    }
  });
}

function employeeDropDocument() {
  $.ajax({
    type: "POST",
    url: "approver_transcription_proc.php",
    data: {
      employeeDropDocument: docId,
    },
    success: function (response) {
      (response == 1) ? $.redirect("employee.php") : console.log("Error droping document");

    }
  });

}



function createPDFCanvaFull(arrayOfImages) {

  $("#createPDFCanvas").empty();
  $("#createPDFCanvas").append('<img src="' + logoPath + '" id="museumLogo"></img>');


  for (let i = 0; i < arrayOfImages.images.length; i++) {

    $("#createPDFCanvas").append('<img src="' + arrayOfImages.images[i] + '" id="img' + i + '"></img>');
    $("#createPDFCanvas").append('<label id="notesText' + i + '">' + arrayOfImages.notesText[i] + '></label>');
    $("#createPDFCanvas").append('<label id="transcText' + i + '">' + arrayOfImages.transcText[i] + '></label>');

  }

}

/**
 * @summary This function will create a pdf file with the document image and text content
 */
function generatePDF(arrayOfImages) {

  let docTitle = $("#txtTitle").html();
  let pdfFileTitle = docTitle.trim() + ".pdf";
  var doc = new jsPDF();

  for (let i = 0; i < arrayOfImages.images.length; i++) {

    //getting data
    imageLoaded = new Image();
    imageLoaded.src = $("#img" + i).attr('src');

    // jsPDF stuff
    //add the museum logo at the top of the page
    doc.addImage(museumLogo, 'jpg', 0, 0, 30, 30);

    //Add the actual loaded image to the pdf
    doc.addImage(imageLoaded, 'jpg', 0, 50, 100, 0);

    // Create a Filled square at the top of the page HEADER
    // doc.setDrawColor(0);
    doc.setFillColor(41, 41, 41);
    doc.rect(30, 0, 230, 30, "F");
    //add the document's title
    doc.setTextColor(163, 136, 105);
    doc.text(35, 15, docTitle);

    //Image transcription text
    doc.setFillColor(255, 255, 255);
    doc.rect(100, 40, 100, 150, "F");

    let docTranscription = $("#transcText" + i).html();
    let lineY = 55;
    let lineX = 105;
    let lineHeight = 4;
    let splitText = doc.splitTextToSize(docTranscription, 350);
    //Transcription Title
    doc.setTextColor();
    doc.setFontSize(15);
    doc.text(lineX, lineY - 6, 'Transcription text:');

    //Transcription Text
    for (let i = 0; i < splitText.length; i++) {
      doc.setFontSize(10);
      doc.text(splitText[i], lineX, lineY);
      lineY = lineHeight + lineY;
    }


    //NOTES
    let docNotes = $("#notesText" + i).html();
    let lineYN = lineY + 10;
    let lineXN = 105;
    let lineHeightN = 4;
    let splitTextN = doc.splitTextToSize(docNotes, 220);
    //Transcription Title
    doc.setTextColor();
    doc.setFontSize(15);
    doc.text(lineXN, lineYN - 6, 'Notes Text: ');

    for (let i = 0; i < splitTextN.length; i++) {
      doc.setFontSize(10);
      doc.text(splitTextN[i], lineXN, lineYN);
      lineYN = lineHeightN + lineYN;
    }

    if (i < arrayOfImages.images.length - 1) {
      doc.addPage();
    }


  }

  // doc.addImage('openseadragon-canvas', 'jpg', 0, 0, 80, 80);
  doc.output('save', pdfFileTitle);
  var file = new File([doc.output('blob')], pdfFileTitle, {
    type: 'application/pdf'
  });
  var url = URL.createObjectURL(file);

  window.open(url);


}