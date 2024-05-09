import { useState } from '@wordpress/element';
import sanitizeHtml from 'sanitize-html';

export const useTables = () => {
	const [error, setError] = useState({
		status: false,
		column: '',
	});

	const triggerColumnError = (column) => {
		setError({
			status: true,
			column,
		});
	};

	const removeColumnError = () => {
		setError({
			status: false,
			column: '',
		});
	};

	const getElementID = (row) => {
		let returnable;

		row.childNodes.forEach((element, index) => {
			switch (element.getAttribute('column')) {
				case 'id':
				case 'ID':
					returnable = element.innerText;
			}
		});

		return sanitizeHtml(returnable);
	};

	const getTableData = (event) => ({
		table: event.currentTarget.parentNode.parentNode.parentNode,
		row: event.currentTarget.parentNode.parentNode,
		column: sanitizeHtml(event.currentTarget.parentNode.getAttribute('column')),
		value: sanitizeHtml(event.currentTarget.innerHTML),
		id: getElementID(event.currentTarget.parentNode.parentNode),
	});

	return { getTableData, triggerColumnError, removeColumnError, error };
};
