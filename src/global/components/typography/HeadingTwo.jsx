/**
 * Add a H2 element
 *
 * @since 1.0.2
 *
 * @param className
 * @param label
 * @param props
 * @returns {JSX.Element}
 * @constructor
 */
export const HeadingTwo = ({ className, label, ...props }) => {
	const defaultClasses = "tada-heading-2";
	return (
		<h2
			className={className ? defaultClasses + " " + className : defaultClasses}
			{...props}
		>
			{label}
		</h2>
	);
};
