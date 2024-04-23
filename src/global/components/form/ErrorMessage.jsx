/**
 * Display error message
 *
 * @param className
 * @param error
 * @returns {JSX.Element}
 * @constructor
 */
export const ErrorMessage = ({ className, error }) => {
	const defaultClasses = "tada-error-message";

	return (
		<p
			className={className ? defaultClasses + " " + className : defaultClasses}
		>
			{error}
		</p>
	);
};
