import { useState } from '@wordpress/element';
import classNames from 'classnames';
import ContentEditable from 'react-contenteditable';

export const TableColumn = ({
	data,
	editable,
	column,
	onBlur,
	row,
	error,
	updateOriginalValue,
}) => {
	const [columnData, setColumnData] = useState(data);

	const handleChange = (event) => {
		setColumnData(event.target.value);
		updateOriginalValue(row.id, column, event.target.value);
	};

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
				onChange={handleChange}
				onBlur={onBlur}
			/>
		</td>
	) : (
		<td column={column}>{data}</td>
	);
};
