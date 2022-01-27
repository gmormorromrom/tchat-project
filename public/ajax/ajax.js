$(document).ready(function () {
  $("#addCommentModal").on("shown.bs.modal", function (event) {
    var button = null;
    var urlStr = null;
    var request = null;

    // $(".modal-body").html(imgNew);
    button = $(event.relatedTarget);
    // Button that triggered the modal
    // var recipient = button.data('whatever') // Extract info from data-* attributes
    urlStr = button.data("url");
    console.log(urlStr);
    request = $.ajax({
      url: urlStr,
      method: "GET",
      contentType: false,
      processData: false,
      cache: true,
      async: true,
    });
    request.done(function (result) {
      $(".modal-body").find(".img").remove();
      $(".modal-body").append(result["view"]);
      // valeur du jeton
      var valCsrf = $("#csrf_form").val();
      console.log("la valeur du csrf : " + valCsrf);
    });
    request.fail(function () {
      console.log("error");
    });
    request.always(function (result) {
      console.log("complete");
    });
  });
  // fonction sleep
  function sleep(milliseconds) {
    var start = new Date().getTime();
    for (var i = 0; i < 1e7; i++) {
      if (new Date().getTime() - start > milliseconds) {
        break;
      }
    }
  }
  /* NOUVELLE enregistrement de la nouvelle tache depuis le modal */
  $(document).on("submit", "#form_comment_new", function (eventNew) {
    eventNew.preventDefault();  
    var post_url = null;
    var request_method = null;
    var requete = null;
    // $('.modal-body').empty();
    post_url = $(this).attr("action");
    console.log(post_url);
    request_method = $(this).attr("method");
    // parametres = $(this).serialize();
    requete = $.ajax({
      url: post_url,
      method: "POST",
      async: true,  
      data: $(this).serialize()
      // dataType: 'json' // what type of data do we expect back from the server
    });
    requete.done(function (rdata, textStatus, xhr) {
        // console.log(JSON.stringify(rdata['view']));
        if ( !rdata['code']) {
          $(document).find(".modal-body").html(rdata['view']); 
        } else {
          // var dataResult = JSON.parse(rdata['view']); 
          $(document).find('#addCommentModal').modal('toggle');   
          location.reload();		 
        }
    });
    requete.fail(function () {
      console.log("error");
    });
    requete.always(function (result) {
      console.log("complete");
    }); 
  });
});
