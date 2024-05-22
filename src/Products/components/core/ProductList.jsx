import { __ } from '@wordpress/i18n';
import { Table, HeadingTwo, useTables } from '@global';

export const ProductList = ({ setIsEdit }) => {
	const { rows, headers } = useTables(
		window.lchb_products.products,
		window.lchb_products.fields,
	);

	return (
		<>
			<HeadingTwo label={__('Products', 'licensehub')} />
			<Table
				headers={headers[0]}
				rows={rows}
				editable={false}
				deletable={false}
				setIsEdit={setIsEdit}
			/>
		</>
	);
};
