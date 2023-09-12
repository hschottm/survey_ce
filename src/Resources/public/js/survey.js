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
  let submit = document.querySelector('input.submit'),
    // the start page of a survey contains the element input=value=tl_survey_form, this value indicates that it is the start page of a survey with TAN query
    form = document.querySelector("input[value='tl_survey_form']"),
    // do we have a tan?
    tan = document.querySelector("input#tan"),
    // if the TAN is valid?
    tanerror = document.querySelector("p.tl_error"),
    // if autostart allowed?
    autostart = document.querySelector("input[name='allowAutostart']");

  // if all conditions are met - start the survey automatically
  if(form && tan.value.length > 0 && !tanerror && autostart.value === '1') submit.click();
});
