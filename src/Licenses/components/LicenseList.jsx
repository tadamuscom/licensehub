import apiFetch from '@wordpress/api-fetch';
import { __ } from '@wordpress/i18n';
import { Table, HeadingTwo, useTables } from '@global';
import { toastOptions } from '@global';
import { toast } from 'react-toastify';

export const LicenseList = () => {
	const { getTableData, removeRow, updateColumn, rows, headers } = useTables(
		window.lchb_license_keys.keys,
		window.lchb_license_keys.fields,
	);

	const updateOriginalValue = (rowID, column, value) => {
		window.lchb_license_keys.keys = window.lchb_license_keys.keys.map((row) => {
			if (row.id === rowID) row[column] = value;

			return row;
		});
	};

	const handleBlur = (event) => {
		updateColumn(
			event,
			'/licensehub/v1/update-license-key',
			window.lchb_license_keys.nonce,
			{
				pending: __('License key is updating...', 'licensehub'),
				success: __('License key updated', 'licensehub'),
				error: __('Something went wrong', 'licensehub'),
			},
		);
	};

	const handleDelete = async (event) => {
		const { id } = getTableData(event);

		await toast.promise(
			apiFetch({
				path: '/licensehub/v1/delete-license-key',
				method: 'DELETE',
				data: {
					nonce: window.lchb_license_keys.nonce,
					id: id,
				},
			}),
			{
				pending: __('License key is deleting...', 'licensehub'),
				success: __('License key deleted', 'licensehub'),
				error: __('Something went wrong', 'licensehub'),
			},
			toastOptions,
		);

		removeRow(id);
	};

	return (
		<>
			<HeadingTwo>{__('License Keys', 'licensehub')}</HeadingTwo>
			<Table
				headers={headers[0]}
				rows={rows}
				onDelete={handleDelete}
				editable={true}
				onBlur={handleBlur}
				updateOriginalValue={updateOriginalValue}
			/>
		</>
	);
};
