
 document.addEventListener('DOMContentLoaded', function () {
          const bookViewButton = document.getElementById('btnBookView');
          const bookViewDialog = document.getElementById('bookViewDialog');
          const closeDialogButton = document.getElementById('closeDialog');

          bookViewButton.addEventListener('click', function () {
            bookViewDialog.showModal();
            initializeTurnJsBook() // Initialize Turn.js book with images
          });

          closeDialogButton.addEventListener('click', function () {
            bookViewDialog.close();
          });
          //this creates the flipbook the same way they created the OSD Canva
          // This function now doesn't expect parameters
          function initializeTurnJsBook() {
            // Assuming 'pdfArray' is already populated with image URLs for OSD and can be reused for Turn.js
            if (!pdfArray || !pdfArray.length) {
              console.log('No images available for Turn.js book.');
              return;
            }

            // Clears any previous content and reinitializes the book
            $('#book').turn('destroy').empty();

            pdfArray.forEach(image => {
              // Directly use the URL for <img> elements within the pages of the Turn.js book
              const pageElement = $('<div class="page"></div>');
              const imgElement = $('<img>').attr('src', image.url).css({ width: '100%', height: '100%' });
              pageElement.append(imgElement);
              $('#book').turn('addPage', pageElement);
            });

            // Initializes Turn.js if it hasn't been initialized already
            if (!$('#book').turn('is')) {
              $('#book').turn({
                width: 800,
                height: 400,
                autoCenter: true
              });
            }
          }



        });