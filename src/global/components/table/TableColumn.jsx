import { useState } from "@wordpress/element";
import ContentEditable from "react-contenteditable";

export const TableColumn = ({ data, editable, column, onBlur }) => {
	const [columnData, setColumnData] = useState(data);

	const handleChange = (event) => {
		setColumnData(event.target.value);
	};

	switch (column) {
		case "ID":
		case "id":
		case "created_at":
		case "expires_at":
			editable = false;
	}

	return editable ? (
		<td column={column}>
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
