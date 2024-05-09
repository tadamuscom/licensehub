import { useState } from '@wordpress/element';
import sanitizeHtml from 'sanitize-html';

export const useTables = (rawRows, rawHeaders) => {
	const [error, setError] = useState({
		status: false,
		column: '',
	});
	const [rows, setRows] = useState(rawRows);
	const [headers, setHeaders] = useState(rawHeaders);

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

	const removeRow = (id) => {
		setRows((prev) => prev.filter((row) => row.id !== id));
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

	return {
		getTableData,
		triggerColumnError,
		removeColumnError,
		removeRow,
		error,
		rows,
		headers,
	};
};
