/**
 * Display error message
 *
 * @param className
 * @param error
 * @returns {JSX.Element}
 * @constructor
 */
export const ErrorMessage = ({ className, children }) => {
	const defaultClasses = 'font-poppins text-md text-red-500 pl-4 inline-block';

	return (
		<p
			className={className ? defaultClasses + ' ' + className : defaultClasses}>
			{children}
		</p>
	);
};
