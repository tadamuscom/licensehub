export const TableHeader = ({ content }) => {
	return <th>{content.name.replace('_', ' ')}</th>;
};
