$(document).ready(function(){

	var sliderTooltip = function(event, ui) {
	  if (ui.value == null) {
		var curValue = initialValue;
	  } else {
		var curValue = ui.value;
	  }
	  // var money = formatMoney(cur, '.', ',');
	  var money = number_format(curValue);
	  var target = ui.handle || $('#slider1 .ui-slider-handle');
	  var tooltip = '<div class="tooltip2"><div class="tooltip-inner2">' + '£' + money + '</div><div class="tooltip-arrow"></div></div>';
	  $(target).html(tooltip);
	  if (event.originalEvent) {
		calculate(curValue, null);
		//$('.result-holder').addClass('active').find('.resultâ€™).html('Â£' + money);
	  }
	}

	var initialValue = 5000;
	$("#slider1").slider({
	  range: "min",
	  value: initialValue,
	  min: 500,
	  max: 25000,
	  step: 50,
	  create: sliderTooltip,
	  slide: sliderTooltip,
	  change: calculate
	});

	var sliderTooltip2 = function(event, ui) {
	  var curValue2 = ui.value || initialValue2;
	  var target2 = ui.handle || $('#slider2 .ui-slider-handle');
	  var year;
	  if (curValue2 > 1) {
		year = ' years';
	  } else {
		year = ' year';
	  }
	  var tooltip2 = '<div class="tooltip2"><div class="tooltip-inner2">' + curValue2 + year + '</div><div class="tooltip-arrow"></div></div>';
	  $(target2).html(tooltip2);
	  if (event.originalEvent) {
		calculate(null, curValue2);
	  }
	}

	var initialValue2 = 2;
	$("#slider2").slider({
	  range: "min",
	  value: initialValue2,
	  min: 1,
	  max: 5,
	  step: 1,
	  create: sliderTooltip2,
	  slide: sliderTooltip2,
	  change: calculate
	});

	function calculate() {
	  // console.log("update");
	  // console.log("1", $("#slider1").slider("value"));
	  // console.log("2", $("#slider2").slider("value"));
	  years = $("#slider2").slider("value");
	  principalLoanAmount = $("#slider1").slider("value");

	  loanTermMonths = years * 12;
	  rateOfIntereset = 6 / 12 / 100;
	  /* Rate of Annual interest/12/100.
		  If rate of interest is 10.5% per annum, then rateOfIntereset = 10.5/12/100=0.00875 */
	  rateOfInteresetPlusOne = rateOfIntereset + 1;
	  onePlusRateOfInteresetPowerLoanTermMonths = Math.pow(rateOfInteresetPlusOne, loanTermMonths);

	  emi = principalLoanAmount * rateOfIntereset *
		onePlusRateOfInteresetPowerLoanTermMonths / (onePlusRateOfInteresetPowerLoanTermMonths - 1);

	  totalPayment = emi * loanTermMonths;

	  $("#total-amount").html(" &pound;" + number_format(totalPayment, 2));
	  $("#emi").html(" &pound;" + number_format(emi, 2));
	}

	function number_format(number, decimals, dec_point, thousands_sep) {
	  var n = !isFinite(+number) ? 0 : +number,
		prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
		sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
		dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
		toFixedFix = function(n, prec) {
		  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
		  var k = Math.pow(10, prec);
		  return Math.round(n * k) / k;
		},
		s = (prec ? toFixedFix(n, prec) : Math.round(n)).toString().split('.');
	  if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	  }
	  if ((s[1] || '').length < prec) {
		s[1] = s[1] || '';
		s[1] += new Array(prec - s[1].length + 1).join('0');
	  }
	  return s.join(dec);
	}

});
