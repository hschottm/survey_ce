jQuery.noConflict();
jQuery(function ($) {
  $(".matrix td input").each(function (a, b) {
    $(b).parent().click(function (a) {
      "radio" == $(b).attr("type") ? $(b).parent().parent().find("input[type=radio]").each(function (a, c) {
        $(c)[0] != $(b)[0] ? $(c).prop("checked", !1) : $(c).prop("checked", !0)
      }) : "checkbox" == $(b).attr("type") && $(b).parent().parent().find("input[type=checkbox]").each(function (c, d) {
        $(d)[0] == $(b)[0] && "TD" == a.target.nodeName && $(d).prop("checked", !$(d).prop("checked"))
      })
    })
  });

  // perform autostart of personalized survey with TAN
  // at first check some boundary conditions

  // we need a div class ce_survey
  // we need a form inside
  // we need a form action with pattern /xxxxxx.html?start=now

  /*
  noch nicht begonnen = erste seite

  <input type="hidden" name="FORM_SUBMIT" value="tl_survey_form">
	<input type="hidden" name="REQUEST_TOKEN" value="_zQvpDq81UrfTaFRtLxr2OlXJ6kurHwOBEz7BdZPKro">


  umfrage hat begonnen

  <input type="hidden" name="FORM_SUBMIT" value="tl_survey">
  <input type="hidden" name="REQUEST_TOKEN" value="_zQvpDq81UrfTaFRtLxr2OlXJ6kurHwOBEz7BdZPKro">
  <input type="hidden" name="survey" value="6">
	<input type="hidden" name="page" value="1">
	<input type="hidden" name="pin" value="Q5mLl0">
   */

  let submit = document.querySelector('input.submit');
  console.log(submit);
  let form = document.querySelector("input[value='tl_survey_form']");
  console.log(form);

  if(form) {
console.log('start form gefunden');
    submit.click();
  } else {
console.log('keine startform ');
  }
});
