import { useState } from '@wordpress/element';
import sanitizeHtml from 'sanitize-html';

/**
 * Hook to manage tables
 *
 * @param rawRows
 * @param rawHeaders
 * @returns {{headers: unknown, removeColumnError: (function(*, *): void), triggerColumnError: triggerColumnError, removeRow: (function(*): void), rows: *, getTableData: (function(*): {column: string|*, row: *, id: string|*, value: string|*, table: *})}}
 */
export const useTables = (rawRows, rawHeaders) => {
	/**
	 * Process the rows and add them to state
	 */
	const [rows, setRows] = useState(() => {
		return rawRows.map((row) => {
			return Object.entries(row).map(([columnName, columnValue]) => {
				return { name: columnName, value: columnValue, error: false };
			});
		});
	});

	/**
	 * Process the headers and add them to state
	 */
	const [headers, setHeaders] = useState(rawHeaders);

	/**
	 * Remove a row from the table
	 *
	 * @param id
	 */
	const removeRow = (id) =>
		setRows((prev) => prev.filter((row) => row[0].value !== id));

	/**
	 * Change the error status of a column
	 *
	 * @param column
	 * @param id
	 * @param status
	 * @param force
	 */
	const changeErrorStatus = (column, id, status, force) => {
		setRows((prev) => {
			return rawRows.map((row, index) => {
				return Object.entries(row).map(
					([columnName, columnValue], secondIndex) => {
						const currentErrorStatus = prev[index][secondIndex].error;
						const isTargetColumn = columnName === column && row.id === id;

						if (isTargetColumn && force)
							return { name: columnName, value: columnValue, error: status };

						if (isTargetColumn)
							return { name: columnName, value: columnValue, error: status };

						if (currentErrorStatus)
							return { name: columnName, value: columnValue, error: true };

						return { name: columnName, value: columnValue, error: false };
					},
				);
			});
		});
	};

	/**
	 * Trigger an error on a column
	 *
	 * @param column
	 * @param id
	 */
	const triggerColumnError = (column, id) =>
		changeErrorStatus(column, id, true);

	/**
	 * Remove an error from a column
	 *
	 * @param column
	 * @param id
	 */
	const removeColumnError = (column, id) =>
		changeErrorStatus(column, id, false, true);

	/**
	 * Get the ID of the element
	 *
	 * @param row
	 * @returns {string|*}
	 */
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

	/**
	 * Get the data from the table
	 *
	 * @param event
	 * @returns {{column: (string|*), row: ParentNode, id: (string|*), value: (string|*), table: ParentNode}}
	 */
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
