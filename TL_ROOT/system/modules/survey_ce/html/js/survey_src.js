/**
 * Class Survey
 *
 * Provide methods to handle back end tasks.
 * @copyright  Helmut Schottmüller 2008
 * @author     Helmut Schottmüller <typolight@aurealis.de>
 * @package    Backend
 */
var Survey =
{
	init: function()
	{
		$$('.matrix td input').each(function (item, index) { item.getParent().addEvent('click', Survey.checkMatrixCell); });
		$$('.matrix td input').each(function (item, index) { item.addEvent('click', Survey.checkMatrixCellInput); });
	},
	
	checkMatrixCell: function(e)
	{
		children = this.getChildren('input');
		children.each(function (item, index) { if (item.type == 'checkbox') { item.checked = !item.checked; } else if (item.type == 'radio') { item.checked = true; } });
	},

	checkMatrixCellInput: function(e)
	{
		if (this.type == 'checkbox')
		{
			this.checked = !this.checked;;
		}
	}
};