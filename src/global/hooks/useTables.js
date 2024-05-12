import apiFetch from '@wordpress/api-fetch';
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { toast } from 'react-toastify';
import sanitizeHtml from 'sanitize-html';
import { toastOptions } from '@global/constants';

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
	 */
	const changeErrorStatus = (column, id, status) => {
		setRows((prev) => {
			return rawRows.map((row, index) => {
				return Object.entries(row).map(
					([columnName, columnValue], secondIndex) => {
						const currentErrorStatus = prev[index][secondIndex].error;
						const isTargetColumn = columnName === column && row.id === id;

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
		changeErrorStatus(column, id, false);

	/**
	 * Get the ID of the element
	 *
	 * @param row
	 * @returns {string|*}
	 */
	const getElementID = (row) => {
		let returnable;

		row.childNodes.forEach((element) => {
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

	/**
	 * Update a column in the table and in the backend
	 *
	 * @param event
	 * @param apiPath
	 * @param nonce
	 * @param toastMessages
	 */
	const updateColumn = async (event, apiPath, nonce, toastMessages) => {
		const { column, value, id } = getTableData(event);
		removeColumnError(column, id);

		if (column === 'status') {
			const acceptedStatuses = ['active', 'inactive'];

			if (!acceptedStatuses.includes(value)) {
				triggerColumnError(column, id);

				return toast.error(
					__("Invalid status. Use 'active' or 'inactive'", 'licensehub'),
					toastOptions,
				);
			}
		}

		if (column === 'user_id') {
			let userExists = true;

			try {
				await apiFetch({
					path: `/wp/v2/users/${value}`,
				});
			} catch {
				triggerColumnError(column, id);

				toast.error(__('Invalid user ID', 'licensehub'), toastOptions);
				userExists = false;
			}

			if (!userExists) return;
		}

		await toast.promise(
			apiFetch({
				path: apiPath,
				method: 'PUT',
				data: {
					nonce: nonce,
					id: id,
					column: column,
					value: value,
				},
			}),
			toastMessages,
			toastOptions,
		);
	};

	return {
		getTableData,
		triggerColumnError,
		removeColumnError,
		removeRow,
		updateColumn,
		rows,
		headers,
	};
};
