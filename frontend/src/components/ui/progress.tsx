import * as React from "react"
import { cn } from "@/lib/utils"

interface ProgressProps extends React.HTMLAttributes<HTMLDivElement> {
  value?: number
}

const Progress = React.forwardRef<HTMLDivElement, ProgressProps>(
  ({ className, value = 0, ...props }, ref) => {
    const getProgressColor = () => {
      if (value >= 100) return "bg-green-500"
      return "bg-[#137fec]"
    }

    return (
      <div
        ref={ref}
        className={cn(
          "w-full bg-white/10 rounded-full h-1.5",
          className
        )}
        {...props}
      >
        <div
          className={cn("h-1.5 rounded-full transition-all duration-300", getProgressColor())}
          style={{ width: `${Math.min(value, 100)}%` }}
        />
      </div>
    )
  }
)
Progress.displayName = "Progress"

export { Progress }
