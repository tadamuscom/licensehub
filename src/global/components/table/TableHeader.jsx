export const TableHeader = ({ content }) => {
	if (content.hidden) return null;

	return <th>{content.name.replace('_', ' ')}</th>;
};
