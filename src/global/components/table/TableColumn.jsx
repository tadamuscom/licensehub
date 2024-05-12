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
	const [isEditable, setIsEditable] = useState(() => {
		if (!column) return false;
		if (!editable) return false;

		return column.editable;
	});

	const handleChange = (event) => {
		setColumnData(event.target.value);
		updateOriginalValue(row[0].value, column.name, event.target.value);
	};

	if (!column) return null;

	return isEditable ? (
		<td
			column={column.name}
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
		<td column={column.name}>{columnData}</td>
	);
};
