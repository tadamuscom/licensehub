import { useState } from '@wordpress/element';
import classNames from 'classnames';
import ContentEditable from 'react-contenteditable';

export const TableColumn = ({
	data,
	editable,
	column,
	onBlur,
	row,
	updateOriginalValue,
}) => {
	const [columnData, setColumnData] = useState(data.value);

	const handleChange = (event) => {
		setColumnData(event.target.value);
		updateOriginalValue(row[0].value, column, event.target.value);
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
				'border-2 border-red-500': data.error,
			})}>
			<ContentEditable
				html={columnData}
				onChange={handleChange}
				onBlur={onBlur}
			/>
		</td>
	) : (
		<td column={column}>{columnData}</td>
	);
};
