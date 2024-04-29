/**
 * Display success message
 *
 * @param className
 * @param error
 * @returns {JSX.Element}
 * @constructor
 */
export const SuccessMessage = ({ className, children, inline = false }) => {
	const displayClass = inline ? 'pl-4 inline-block' : 'ml-1 mt-1 block';
	const defaultClasses = `font-poppins text-sm text-green-800 ${displayClass}`;

	return (
		<p
			className={className ? defaultClasses + ' ' + className : defaultClasses}>
			{children}
		</p>
	);
};
