jQuery(document).ready(function($) {
    var table = $('#wpdock_il_table').DataTable({
        pageLength: 10,
        lengthMenu: [[10, 20, 100, 200, -1], [10, 20, 100, 200, "All"]],
        dom: '<"top"if>Brt<"bottom"lp><"clear">',
        buttons: [

            { extend: 'csv',
                text: '<div class="export-csv"><svg enable-background="new 0 0 64 64" viewBox="0 0 64 64"  xmlns="http://www.w3.org/2000/svg"><g id="Layer_33" fill="#00a651"><path d="m56.12 31.38h-2.87v-26.26c0-.55-.45-1-1-1h-40.5c-.55 0-1 .45-1 1v26.26h-2.87c-.56 0-1 .44-1 1v19.24c0 .56.44 1 1 1h2.87v6.26c0 .55.45 1 1 1h40.5c.55 0 1-.45 1-1v-6.26h2.87c.56 0 1-.44 1-1v-19.24c0-.56-.44-1-1-1zm-4.87 26.5h-38.5v-5.26h38.5zm-30.8-13.33c.44.53 1 .78 1.71.78.52 0 .95-.16 1.32-.49s.65-.859.82-1.59c.01-.07.06-.13.12-.16.06-.04.13-.05.2-.03l2.1.671c.13.039.2.18.16.31-.33 1.22-.9 2.14-1.69 2.74-.8.6-1.81.899-3.01.899-1.49 0-2.73-.51-3.7-1.54-.95-1.01-1.44-2.42-1.44-4.159 0-1.851.49-3.311 1.45-4.341.97-1.029 2.26-1.56 3.82-1.56 1.38 0 2.51.42 3.38 1.229.52.49.91 1.19 1.16 2.091.02.069.01.14-.02.199-.04.061-.09.101-.16.11l-2.14.521c-.07.01-.14 0-.19-.03-.06-.04-.1-.09-.12-.16-.11-.5-.34-.89-.7-1.18s-.79-.431-1.32-.431c-.72 0-1.29.25-1.74.771-.46.53-.69 1.399-.69 2.62 0 1.29.23 2.21.68 2.73zm13.65-.91c-.12-.14-.35-.27-.67-.39-.24-.08-.82-.24-1.71-.46-1.22-.3-2.05-.67-2.56-1.12-.71-.64-1.07-1.43-1.07-2.34 0-.6.17-1.16.51-1.68.33-.511.82-.91 1.45-1.181.61-.26 1.35-.39 2.21-.39 1.4 0 2.48.32 3.21.95s1.12 1.489 1.16 2.54c0 .14-.11.26-.24.26l-2.17.1c-.13 0-.24-.09-.26-.21-.08-.489-.25-.84-.5-1.04-.26-.199-.67-.31-1.22-.31-.57 0-1.02.12-1.33.34-.17.12-.25.271-.25.47 0 .181.08.33.24.46.16.141.62.37 1.89.67 1.07.25 1.87.511 2.4.79.54.29.97.681 1.28 1.181s.46 1.12.46 1.84c0 .66-.18 1.29-.55 1.87-.37.569-.89 1.01-1.56 1.29-.66.27-1.47.41-2.44.41-1.41 0-2.52-.341-3.29-1-.77-.671-1.24-1.641-1.38-2.9-.01-.07.01-.14.05-.19s.1-.09.17-.09l2.11-.21c.14-.01.25.08.27.21.11.641.35 1.12.69 1.41s.81.44 1.41.44c.63 0 1.11-.13 1.41-.391.3-.239.45-.52.45-.84 0-.199-.06-.349-.17-.489zm3.07-7.04c-.02-.08-.01-.159.03-.229.05-.07.13-.11.21-.11h2.35c.11 0 .2.07.24.17l2.47 7.24 2.4-7.24c.03-.1.13-.17.24-.17h2.3c.08 0 .15.04.2.11s.06.149.03.229l-3.84 10.73c-.04.1-.13.17-.24.17h-2.31c-.11 0-.2-.07-.24-.17zm14.08-5.22h-38.5v-25.26h38.5z"/><path d="m46.75 13.375h-29.5c-.553 0-1-.447-1-1s.447-1 1-1h29.5c.553 0 1 .447 1 1s-.447 1-1 1z"/><path d="m46.75 20.375h-29.5c-.553 0-1-.447-1-1s.447-1 1-1h29.5c.553 0 1 .447 1 1s-.447 1-1 1z"/><path d="m46.75 27.375h-29.5c-.553 0-1-.447-1-1s.447-1 1-1h29.5c.553 0 1 .447 1 1s-.447 1-1 1z"/></g></svg><div>CSV</div></div>',
                className: 'btn-csv',
                exportOptions: {
                    columns: ':visible:not(.no-export)'  // Exclude any columns that have a 'no-export' class
                }

            }
        ],
        columnDefs: [
            {
                targets: 0,
                orderable: true,
                className: 'center-text'
            },
            {
                targets: 1,
                orderable: false
            },
            {
                targets: 5,
                orderable: true,
                render: function(data, type, row) {
                    if(type === 'sort' || type === 'type') {
                        var div = document.createElement("div");
                        div.innerHTML = data;
                        var number = div.getElementsByClassName("size")[0].innerText;
                        return parseInt(number);
                    }
                    return data;
                }
            },
            {
                targets: 7,
                orderable: false
            }

        ],
		"drawCallback": function (settings) {

            il_update();
        },

    });
	

    function il_update(){
        $('body').on('click', '.actions .edit', function() {
            edit(this);
        });

        $('body').on('click','.actions .cancel',  function() {
            edit_cancel(this);
        });

        $('body').on('click', '.actions .il-sumbit', function() {
            edit_submit(this);
        });

        function edit(input){
            const inputId = $(input).data("id");
            var $span = $('.item-'+inputId).find('.editable-text');
            var $input = $('.item-'+inputId).find('.il-editable');
            var $input_filename = $('.item-'+inputId).find('.il-editable-filename');
            var $row = $('.item-'+inputId);

            var currentName = $input_filename.val();
            var baseName = currentName.substring(0, currentName.lastIndexOf('.')); // Strip the extension

            if (hasExtension(currentName)) {
                const fileExtension = currentName.substring(currentName.lastIndexOf('.'));
                var baseName = currentName.substring(0, currentName.lastIndexOf('.')); // Strip the extension
                $input_filename.val(baseName); // Set the input value to only the filename without extension
            } else {
                $input_filename.val(currentName);
            }


            $span.hide();
            $input.show();
            $(input).addClass('il-sumbit');
            $(input).find('.edit_svg').hide();
            $(input).find('.edit_submit').show();
            $row.find('.cancel_container').show();

        }
        function edit_cancel(input){
            const inputId = $(input).data("id");
            var $span = $('.item-'+inputId).find('.editable-text');
            var $input = $('.item-'+inputId).find('.il-editable');
            var $row = $('.item-'+inputId);

            $span.show();
            $input.hide();
            $row.find('.edit').removeClass('il-sumbit');
            $row.find('.edit_svg').show();
            $row.find('.edit_submit').hide();
            $row.find('.cancel_container').hide();
        }

        function edit_submit(input){
            var id = $(input).data('id');
            var row = $('.item-'+id);
            const alt = row.find('.il-editable-alt').val();
            const filename = row.find('.il-editable-filename').val();
            const original_filename = row.find('.et-filename').text();
            const ext = original_filename.substring(original_filename.lastIndexOf('.'));

            $.ajax({
                url: il_params.ajax_url,
                type: 'POST',
                data: {
                    action: 'update_image_details',
                    nonce: il_params.nonce,
                    id: id,
                    alt: alt,
                    filename: filename
                },
                success: function(response) {
                    console.log(response);
                    if(response.data && response.data.success) {


                        Toast.fire({
                            icon: 'success',
                            title: 'Success'
                        });
                        edit_cancel(input);
                        row.find('.editable-text').html(alt);
                        row.find('.et-filename').html(filename+ext);
                    } else {
                        console.error("Failed to update alt text.");
                    }
                }
            });



        }

        function hasExtension(filename) {
            return filename.lastIndexOf('.') !== -1 && filename.lastIndexOf('.') !== 0;
        }


    }
	
});



const Toast = Swal.mixin({
    toast: true,
    position: 'bottom-right',
    iconColor: 'white',
    customClass: {
        popup: 'colored-toast'
    },
    showConfirmButton: false,
    timer: 1500,
    timerProgressBar: true
})