import apiFetch from '@wordpress/api-fetch';
import { __ } from '@wordpress/i18n';
import { toast } from 'react-toastify';
import { Table } from '@global/components/table/Table';
import { HeadingTwo } from '@global/components/typography/HeadingTwo';
import { toastOptions } from '@global/constants';
import { useTables } from '@global/hooks/useTables';

export const LicenseList = () => {
	const { getTableData, removeRow, updateColumn, rows, headers } = useTables(
		lchb_license_keys.keys,
		lchb_license_keys.fields,
	);

	const updateOriginalValue = (rowID, column, value) => {
		lchb_license_keys.keys = lchb_license_keys.keys.map((row) => {
			if (row.id === rowID) row[column] = value;

			return row;
		});
	};

	const handleBlur = async (event) => {
		updateColumn(
			event,
			'/licensehub/v1/update-license-key',
			lchb_license_keys.nonce,
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
					nonce: lchb_license_keys.nonce,
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
				headers={headers}
				rows={rows}
				onDelete={handleDelete}
				editable={true}
				onBlur={handleBlur}
				updateOriginalValue={updateOriginalValue}
			/>
		</>
	);
};
