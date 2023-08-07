// $(".close").on("click", function() {
//     var current_elem = $(this);
//     $.ajax({
//         url: 'ajax_delete_product.php',
//         type: 'post',
//         data: { id: current_elem.attr('data-id') },
//
//         success: function (res) {
//             try {
//                 res = $.parseJSON(res);
//                 if (res.success === true) {
//                     current_elem.closest('.bloc_product').remove();
//                 } else {
//                     console.error("La suppression a échoué.");
//                 }
//             } catch (e) {
//                 console.error("Erreur: " + e.message);
//             }
//         },
//
//     });
// });