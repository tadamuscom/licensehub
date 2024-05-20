import apiFetch from '@wordpress/api-fetch';
import { __ } from '@wordpress/i18n';
import { toast } from 'react-toastify';
import { Table } from '@global/components/table/Table';
import { HeadingTwo } from '@global/components/typography/HeadingTwo';
import { toastOptions } from '@global/constants';
import { useTables } from '@global/hooks/useTables';

export const KeyList = () => {
	const { getTableData, removeRow, updateColumn, rows, headers } = useTables(
		window.lchb_api_keys.keys,
		window.lchb_api_keys.fields,
	);

	const updateOriginalValue = (rowID, column, value) => {
		window.lchb_api_keys.keys = window.lchb_api_keys.keys.map((row) => {
			if (row.id === rowID) row[column] = value;

			return row;
		});
	};

	const handleBlur = (event) => {
		updateColumn(
			event,
			'/licensehub/v1/update-api-key',
			window.lchb_api_keys.nonce,
			{
				pending: __('API key is updating...', 'licensehub'),
				success: __('API key updated', 'licensehub'),
				error: __('Something went wrong', 'licensehub'),
			},
		);
	};

	const handleDelete = async (event) => {
		const { id } = getTableData(event);

		await toast.promise(
			apiFetch({
				path: '/licensehub/v1/delete-api-key',
				method: 'DELETE',
				data: {
					nonce: window.lchb_api_keys.nonce,
					id: id,
				},
			}),
			{
				pending: __('API key is deleting...', 'licensehub'),
				success: __('API key deleted', 'licensehub'),
				error: __('Something went wrong', 'licensehub'),
			},
			toastOptions,
		);

		removeRow(id);
	};

	return (
		<>
			<HeadingTwo label="API Keys" />
			<Table
				headers={headers}
				rows={rows}
				editable={true}
				updateOriginalValue={updateOriginalValue}
				onBlur={handleBlur}
				onDelete={handleDelete}
			/>
		</>
	);
};
