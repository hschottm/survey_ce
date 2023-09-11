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
  let submit = document.querySelector('input.submit');
  // the start page of a survey contains the element input=value=tl_survey_form, this value indicates that it is the start page of a survey with TAN query
  let form = document.querySelector("input[value='tl_survey_form']");
  // start the survey
  if(form) submit.click();
});
