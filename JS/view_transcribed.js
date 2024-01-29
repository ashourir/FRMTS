$(document).ready(function() {

  /*
  * Takes User that clicks on document in 
  * view-transcribed-documents.php to the 
  * transcription.php page where they can
  * download / print 
  */
  $(".doc_thumbnail").on('click', (e) => {
    e.preventDefault();
    let docId = $(e.currentTarget).attr('docId');
    $.redirect('transcription.php', { viewTranscribedDocId: docId });
  })
})//end document.ready

