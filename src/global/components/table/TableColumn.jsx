import { useState } from '@wordpress/element';
import ContentEditable from 'react-contenteditable';

export const TableColumn = ({ data, editable, column, onBlur, error }) => {
	const [columnData, setColumnData] = useState(data);

	switch (column) {
		case 'ID':
		case 'id':
		case 'created_at':
		case 'expires_at':
			editable = false;
	}

	return editable ? (
		<td column={column}>
			<ContentEditable
				html={columnData}
				onChange={(event) => setColumnData(event.target.value)}
				onBlur={onBlur}
			/>
		</td>
	) : (
		<td column={column}>{columnData}</td>
	);
};
