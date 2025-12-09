import * as React from "react"
import { cn } from "@/lib/utils"

const Badge = React.forwardRef<HTMLSpanElement, React.HTMLAttributes<HTMLSpanElement> & {
  variant?: "default" | "easy" | "medium" | "hard"
}>(({ className, variant = "default", ...props }, ref) => {
  const variants = {
    default: "text-gray-400 bg-gray-500/10",
    easy: "text-green-400 bg-green-500/10",
    medium: "text-yellow-400 bg-yellow-500/10",
    hard: "text-red-400 bg-red-500/10",
  }

  return (
    <span
      ref={ref}
      className={cn(
        "inline-flex items-center rounded-full px-2 py-1 text-xs font-medium",
        variants[variant],
        className
      )}
      {...props}
    />
  )
})
Badge.displayName = "Badge"

export { Badge }
