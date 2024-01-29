var coll, currentPage, view_Count, lastPage;

function clearPageControls() {

  switch (currentPage) {
    case 1:
      $('.first_pg').hide();
      $('.prev_pg').hide();
      break;
    case 2:
      $('.first_pg').hide();
      break;
    case (lastPage - 1):
      $('.last_pg').hide();
      break;
    case (lastPage):
      $('.next_pg').hide();
      $('.last_pg').hide();
  }
  if (lastPage <= 3) {
    $('.first_pg').hide();
    $('.prev_pg').hide();
    $('.next_pg').hide();
    $('.last_pg').hide();
  }
}

$(document).ready(function() {

  coll = $('.row__search_bar').data('coll');
  currentPage = $('.row__search_bar').data('current_page');
  view_count = $('.row__search_bar').data('limit');
  lastPage = parseInt($('.page_controls').attr('total'));

  clearPageControls();



  /************************  HANDLE SEARCH INPUT  *************************/
  $('.search_collection').on('input', function() {
    let input = $('.search_collection').val();
    let query = input.toLowerCase().split(/[\s,]+/); //split multiple queries on space or commas
    if (input == null || input == "") {
      $('.doc_thumbnail').each(function() {
        $(this).show();
      });
    } else {
      $('.doc_thumbnail').each(function() {
        let data = $(this).data('search');
        let match = false;
        for (const q of query) {
          if (q == "") {
            continue;
          } else {
            data.filter(function(val) {
              if (val.toLowerCase().includes(q)) {
                match = true;
              }
            });
          }
        }
        if (match) {
          $(this).show();
        } else {
          $(this).hide();
        }
      })
    }
  })


  /************************  HANDLE CHANGES TO PAGECOUNT & COLLECTION  *************************/
  $('.update_filter').on('click', function() {
    let view_count = $('#viewCount').val();
    let collection = $('#collections_list').val();
    let url = window.location.href.replace(location.search, '');
    if (url.search('volunteer') > 0) {
      $.redirect('volunteer.php', { coll: collection, page: 1, count: view_count });
    } else {
      $.redirect('view-transcribed-documents.php', { coll: collection, page: 1, count: view_count });
    }
  })



  /************************  HANDLE PAGE NAVIGATION  *************************/
  $('.page').on('click', function() {

    let req = $(this).attr('page');

    switch (req) {
      case "first_pg":
        req = 1;
        break;
      case "prev_pg":
        req = currentPage - 1;
        break;
      case "next_pg":
        req = currentPage + 1;
        break;
      case "last_pg":
        req = parseInt($(this).attr('num'));
        break;
      default:
        req = req.split('_')[0];
        break;
    }
    let url = window.location.href.replace(location.search, '');
    if (url.search('volunteer') > 0) {
      $.redirect('volunteer.php', { coll: coll, page: req, count: view_count });
    } else {
      $.redirect('view-transcribed-documents.php', { coll: coll, page: req, count: view_count });
    }
  })
})

