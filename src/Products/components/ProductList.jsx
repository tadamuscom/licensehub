import apiFetch from '@wordpress/api-fetch';
import { useState } from '@wordpress/element';
import sanitizeHtml from 'sanitize-html';
import { Table } from '@global/components/table/Table';
import { HeadingTwo } from '@global/components/typography/HeadingTwo';

export const ProductList = () => {
	const [loading, setLoading] = useState(false);

	const tableOnBlur = (event) => {
		const table =
			event.currentTarget.parentNode.parentNode.parentNode.parentNode;
		const row = event.currentTarget.parentNode.parentNode;
		const columnElement = event.currentTarget.parentNode;
		const column = sanitizeHtml(columnElement.getAttribute('column'));
		const value = sanitizeHtml(event.currentTarget.innerHTML);
		const id = getElementID(row);

		setLoading(true);

		if (!column || column.length < 1) {
			setLoading(false);

			return;
		}

		if (column === 'status') {
			const acceptedStatuses = ['active', 'inactive'];

			if (!acceptedStatuses.includes(value)) {
				setLoading(false);

				return;
			}
		}

		const loader = columnAddLoader(row);

		const data = {
			nonce: lchb_products.nonce,
			id: id,
			column: column,
			value: value,
		};

		const beforeUnloadHandler = (event) => {
			event.preventDefault();

			event.returnValue = true;
		};

		window.addEventListener('beforeunload', beforeUnloadHandler);

		apiFetch({
			path: '/tadamus/lchb/v1/update-product',
			method: 'PUT',
			data: data,
		})
			.then((result) => {
				if (!result.success) {
					loader.remove();
				} else {
					loader.remove();
				}

				window.removeEventListener('beforeunload', beforeUnloadHandler);
			})
			.catch((result) => {
				loader.remove();

				window.removeEventListener('beforeunload', beforeUnloadHandler);
			});
	};

	return (
		<>
			<HeadingTwo label="Products" />
			<Table
				headers={lchb_products.fields}
				rows={lchb_products.products}
				editable={true}
				onBlur={tableOnBlur}
			/>
		</>
	);
};
