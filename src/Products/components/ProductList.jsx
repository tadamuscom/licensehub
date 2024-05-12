import apiFetch from '@wordpress/api-fetch';
import { __ } from '@wordpress/i18n';
import { toast } from 'react-toastify';
import { Table } from '@global/components/table/Table';
import { HeadingTwo } from '@global/components/typography/HeadingTwo';
import { toastOptions } from '@global/constants';
import { useTables } from '@global/hooks/useTables';

export const ProductList = () => {
	const { getTableData, removeRow, updateColumn, rows, headers } = useTables(
		lchb_products.products,
		lchb_products.fields,
	);

	const updateOriginalValue = (rowID, column, value) => {
		lchb_products.products = lchb_products.products.map((row) => {
			if (row.id === rowID) row[column] = value;

			return row;
		});
	};

	const handleBlur = async (event) => {
		updateColumn(
			event,
			'/tadamus/lchb/v1/update-product',
			lchb_products.nonce,
			{
				pending: __('Product is loading...', 'licensehub'),
				success: __('Product updated', 'licensehub'),
				error: __('Something went wrong', 'licensehub'),
			},
		);
	};

	const handleDelete = async (event) => {
		const { id } = getTableData(event);

		await toast.promise(
			apiFetch({
				path: '/tadamus/lchb/v1/delete-product',
				method: 'DELETE',
				data: {
					nonce: lchb_products.nonce,
					id: id,
				},
			}),
			{
				pending: __('Product is deleting...', 'licensehub'),
				success: __('Product deleted', 'licensehub'),
				error: __('Something went wrong', 'licensehub'),
			},
			toastOptions,
		);

		removeRow(id);
	};

	return (
		<>
			<HeadingTwo label={__('Products', 'licensehub')} />
			<Table
				headers={headers}
				rows={rows}
				editable={true}
				onBlur={handleBlur}
				onDelete={handleDelete}
				updateOriginalValue={updateOriginalValue}
			/>
		</>
	);
};
