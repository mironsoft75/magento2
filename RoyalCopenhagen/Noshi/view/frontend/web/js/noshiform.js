require([
	'jquery',
	'mage/translate'
], function ($, $t) {

	$(document).ready(function() {
		var $purpose = $('select#purpose');
		var $ribbon = $('select#ribbon');
		var $noshiCode = $('select#noshi-code');
		var $inscription = $('select#inscription');
		var $workRequired = $('input[name="work_required"]');
		var $receiver_name = $('#reciver_name1, #reciver_name2');
		var $bag = $('input[name="bag"]');

		var $previewButton = $('#preview-button');

		var PLEASE_SELECT = $t('Please select');

		var purpose = {
			"celebration": {
				text: $t('For Celebration'),
				ribbon: ['works','ribbon','no works']
			},
			"buddhist memorial": {
				text: $t('For the Buddhist memorial service'),
				ribbon: ['works','no works']
			}
		};

		var ribbon = {
			'works': {
				text: $t('Works'),
				nextNode: 'noshiCode'
			},
			'no works': {
				text: $t('No Works ribbon'),
				nextNode: 'bag'
			},
			'ribbon': {
				text: $t('Ribbon packing'),
				nextNode: 'bag'
			}
		};

		var purposeValue = {
			"celebration": {
				"marriage": $t("Your marriage"),
				"celebration": $t("Celebration"),
				"baby gifts": $t("Your baby gifts"),
				"greetings": $t("Greeting"),
				"disease recovery": $t("Celebration of the disease recovery")
			},
			"buddhist memorial": {
				"condolence": $t("Each condolence")
			}
		};

		var inscriptionValue = {
			"marriage": {
				'kotobuki': $t("Kotobuki"),
				"family celebration": $t("Family Celebration"),
				"holidays": $t("Your holidays")
			},
			"celebration": {
				"holidays": $t("Your holidays"),
				"family celebration": $t("Family celebration")
			},
			"baby gifts": {
				"holidays": $t("Your holidays"),
				"family celebration": $t("Family celebration")
			},
			"greetings": {
				"new years": $t("Your New Year's"),
				"hazime ataru": $t("Hazime Ataru control"),
				"year-end gift": $t("Year-end gift"),
				"thank you gift": $t("Thanks"),
				"souvenir": $t('Souvenir'),
				"little gift": $t("Little gift")
			},
			"disease recovery": {
				"kaiki family celebration": $t("Kaiki family celebration"),
				"kaiki congratulation": $t("Kaiki congratulation")
			},
			"condolence": {
				"zhi": $t("Zhi")
			}
		};

		$('input[name="required_gift_wrapping"]').change(function()
		{
			//$('input[name="noshi_require_package_no"]').prop('checked', false);
			disableAndResetFields(1);

			if (this.value == 'true')
			{
				$purpose.append(function()
				{
					var _str = getOptionHtml(PLEASE_SELECT, '');

					$.each(purpose, function(key, val)
					{
						_str += getOptionHtml(val.text, key);
					});

					return _str;
				});

				setElementDisabled($purpose, false);
			}
		});

		$purpose.change(function()
		{
			disableAndResetFields(2);

			var _val = this.value;

			if (_val != '')
			{
				$ribbon.append(function()
				{
					var _str = getOptionHtml(PLEASE_SELECT, '');

					if (purpose[_val].ribbon)
					{
						var _array = purpose[_val].ribbon;

						for (var i=0; i<_array.length; i++)
						{
							_str += getOptionHtml(ribbon[_array[i]].text, _array[i]);
						}
					}

					return _str;
				});

				setElementDisabled($ribbon, false);
			}
		});


		$ribbon.change(function()
		{
			disableAndResetFields(3);

			if (ribbon[this.value] && ribbon[this.value].nextNode)
			{
				if (ribbon[this.value].nextNode == 'noshiCode')
				{
					$noshiCode.append(function()
					{
						var _str = getOptionHtml(PLEASE_SELECT, '');

						if (purposeValue[$purpose.val()])
						{
							$.each(purposeValue[$purpose.val()], function(key, val)
							{
								_str += getOptionHtml(val, key);
							});
						}

						return _str;
					});

					setElementDisabled($noshiCode, false);
				}
				else
				{
					setElementDisabled($bag, false);
				}
			}
		});

		$noshiCode.change(function()
		{
			disableAndResetFields(4);

			var _val = inscriptionValue[this.value];

			if (_val)
			{
				$inscription.append(function()
				{
					var _str = getOptionHtml(PLEASE_SELECT, '');

					$.each(_val, function(key, val)
					{
						_str += getOptionHtml(val, key);
					});

					return _str;
				});

				setElementDisabled($inscription, false);
			}
		});

		$inscription.change(function()
		{
			disableAndResetFields(5);

			if (this.value != '')
			{
				setElementDisabled($workRequired, false);
				setElementDisabled($bag, false);
				setElementDisabled($previewButton, false);
			}
		});

		$workRequired.change(function()
		{
			disableAndResetFields(6);

			if (this.value == 'true')
			{
				setElementDisabled($receiver_name, false);
			}
		});

		function setElementDisabled(elem, bool)
		{
			elem.prop('disabled', bool);
		};

		function getOptionHtml(text, val)
		{
			return '<option value="' + val + '">' + text + '</option>';
		}

		function disableAndResetFields(level)
		{
			switch(level)
			{
				case 1:

					setElementDisabled($purpose, true);
					$purpose.empty();

				case 2:

					setElementDisabled($ribbon, true);
					$ribbon.empty();

				case 3:

					setElementDisabled($noshiCode, true);
					$noshiCode.empty();

				case 4:

					setElementDisabled($inscription, true);
					$inscription.empty();

				case 5:

					setElementDisabled($workRequired, true);
					$workRequired.filter('[value="false"]').prop('checked', 'checked');

					setElementDisabled($receiver_name, true);
					$receiver_name.val('');

					setElementDisabled($bag, true);
					$bag.filter('[value="false"]').prop('checked', 'checked');

					setElementDisabled($previewButton, true);

					break;

				case 6:

					setElementDisabled($receiver_name, true);
					$receiver_name.val('');

					break;

				default:

					break;
			}
		}
	});
});