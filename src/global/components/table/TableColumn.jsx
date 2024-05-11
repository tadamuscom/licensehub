import { useState } from '@wordpress/element';
import classNames from 'classnames';
import ContentEditable from 'react-contenteditable';

export const TableColumn = ({ data, editable, column, onBlur, row, error }) => {
	const [columnData, setColumnData] = useState(data);

	switch (column) {
		case 'ID':
		case 'id':
		case 'created_at':
		case 'expires_at':
			editable = false;
	}

	return editable ? (
		<td
			column={column}
			className={classNames({
				'border-2 border-red-500':
					error && error.column === column && error.rowID === row.id,
			})}>
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
