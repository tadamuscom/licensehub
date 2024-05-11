import apiFetch from '@wordpress/api-fetch';
import { __ } from '@wordpress/i18n';
import { toast } from 'react-toastify';
import { Table } from '@global/components/table/Table';
import { HeadingTwo } from '@global/components/typography/HeadingTwo';
import { toastOptions } from '@global/constants';
import { useTables } from '@global/hooks/useTables';

export const ProductList = () => {
	const {
		getTableData,
		triggerColumnError,
		removeRow,
		removeColumnError,
		rows,
		headers,
	} = useTables(lchb_products.products, lchb_products.fields);

	const updateOriginalValue = (rowID, column, value) => {
		lchb_products.products = lchb_products.products.map((row) => {
			if (row.id === rowID) row[column] = value;

			return row;
		});
	};

	const handleBlur = async (event) => {
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
				path: '/tadamus/lchb/v1/update-product',
				method: 'PUT',
				data: {
					nonce: lchb_products.nonce,
					id: id,
					column: column,
					value: value,
				},
			}),
			{
				pending: __('Product is loading...', 'licensehub'),
				success: __('Product updated', 'licensehub'),
				error: __('Something went wrong', 'licensehub'),
			},
			toastOptions,
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
