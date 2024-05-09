import apiFetch from '@wordpress/api-fetch';
import { __ } from '@wordpress/i18n';
import { toast } from 'react-toastify';
import { Table } from '@global/components/table/Table';
import { HeadingTwo } from '@global/components/typography/HeadingTwo';
import { useTables } from '@global/hooks/useTables';

export const ProductList = () => {
	const { getTableData, triggerColumnError, error } = useTables();

	const tableOnBlur = async (event) => {
		const { column, value, id } = getTableData(event);

		if (column === 'status') {
			const acceptedStatuses = ['active', 'inactive'];

			if (!acceptedStatuses.includes(value)) {
				triggerColumnError(column);

				toast.error(
					__("Invalid status. Use 'active' or 'inactive'", 'licensehub'),
					{
						position: 'bottom-right',
						autoClose: 2000,
					},
				);

				return;
			}
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
			{
				position: 'bottom-right',
				autoClose: 1500,
			},
		);
	};

	return (
		<>
			<HeadingTwo label={__('Products', 'licensehub')} />
			<Table
				headers={lchb_products.fields}
				rows={lchb_products.products}
				editable={true}
				onBlur={tableOnBlur}
				error={error}
			/>
		</>
	);
};
