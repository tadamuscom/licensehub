import { useState } from '@wordpress/element';
import sanitizeHtml from 'sanitize-html';

export const useTables = (rawRows, rawHeaders) => {
	const [rows, setRows] = useState(() => {
		return rawRows.map((row) => {
			return Object.entries(row).map(([columnName, columnValue]) => {
				return { name: columnName, value: columnValue, error: false };
			});
		});
	});
	const [headers, setHeaders] = useState(rawHeaders);

	const removeRow = (id) =>
		setRows((prev) => prev.filter((row) => row[0].value !== id));

	const changeErrorStatus = (column, id, status) => {
		setRows((prev) => {
			return rawRows.map((row, index) => {
				return Object.entries(row).map(
					([columnName, columnValue], secondIndex) => {
						if (columnName === column && row.id === id)
							return { name: columnName, value: columnValue, error: true };
						if (prev[index][secondIndex].error)
							return { name: columnName, value: columnValue, error: true };

						return { name: columnName, value: columnValue, error: false };
					},
				);
			});
		});
	};

	const triggerColumnError = (column, id) => {
		console.log('trigger error');
		changeErrorStatus(column, id, true);
	};

	const removeColumnError = (column, id) =>
		changeErrorStatus(column, id, false);

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
		rows,
		headers,
	};
};
